<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Models\ChatGroup;

use App\Models\GroupMember;

use App\Models\GroupMessage;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Events\GroupMessageSent;

class GroupController extends Controller
{
    /*
    =========================================
    CREATE GROUP
    =========================================
    */

    public function create(Request $request)
    {
        $request->validate([

            'name' =>

                'required|string|max:255',

            'image' =>

                'nullable|image|max:2048',

            'members' =>

                'required|array',
        ]);

        /*
        upload image
        */

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store(
                    'groups',
                    'public'
                );
        }

        /*
        create group
        */

        $group = ChatGroup::create([

            'name' =>

                $request->name,

            'image' =>

                $imagePath,

            'admin_id' =>

                auth()->id(),
        ]);


        /*
        add creator
        */

        GroupMember::create([

            'group_id' =>

                $group->id,

            'user_id' =>

                auth()->id(),

            'is_admin' => true,
        ]);

        /*
        add members
        */

        foreach ($request->members as $memberId) {

            if ($memberId != auth()->id()) {

                GroupMember::create([

                    'group_id' =>

                        $group->id,

                    'user_id' =>

                        $memberId,

                    'is_admin' => false,
                ]);
            }
        }

        return response()->json([

            'status' => true,

            'group' => $group,
        ]);
    }

    /*
    =========================================
    MY GROUPS
    =========================================
    */

    public function myGroups()
    {
        $groups = ChatGroup::whereHas(

            'members',

            function ($q) {

                $q->where(
                    'user_id',
                    auth()->id()
                );
            }
        )
        ->with([

            'members',
            'admin'
        ])
        ->latest()
        ->paginate(20);

        return response()->json([

            'status' => true,

            'groups' => $groups,
        ]);
    }

    /*
    =========================================
    GROUP DETAILS
    =========================================
    */

    public function details($groupId)
    {
        $group = ChatGroup::with([

            'members',
            'admin'
        ])->findOrFail($groupId);

        return response()->json([

            'status' => true,

            'group' => $group,
        ]);
    }

    /*
    =========================================
    SEND GROUP MESSAGE
    =========================================
    */

    public function sendMessage(
        Request $request,
        $groupId
    ) {

        $request->validate([

            'message' =>

                'nullable|string',

            'type' =>

                'required|in:text,image,video,file,audio,location',

            'file' =>

                'nullable|file|max:51200',

            'latitude' =>

                'nullable|numeric',

            'longitude' =>

                'nullable|numeric',
        ]);

        /*
        check membership
        */

        $isMember = GroupMember::where(

            'group_id',
            $groupId

        )->where(

            'user_id',
            auth()->id()

        )->exists();

        if (! $isMember) {

            return response()->json([

                'status' => false,

                'message' => 'Not member in this group'
            ], 403);
        }

        /*
        upload file
        */

        $filePath = null;

        if ($request->hasFile('file')) {

            $filePath = $request
                ->file('file')
                ->store(
                    'group-messages',
                    'public'
                );
        }

        /*
        create message
        */

        $message = GroupMessage::create([

            'group_id' =>

                $groupId,

            'sender_id' =>

                auth()->id(),

            'message' =>

                $request->message,

            'type' =>

                $request->type,

            'file' =>

                $filePath,

            'latitude' =>

                $request->latitude,

            'longitude' =>

                $request->longitude,
        ]);

                broadcast(
    new GroupMessageSent($message)
)->toOthers();


        return response()->json([

            'status' => true,

            'message' => $message->load(
                'sender'
            ),
        ]);
    }

    /*
    =========================================
    GROUP MESSAGES
    =========================================
    */

    public function messages($groupId)
    {
        $messages = GroupMessage::where(
            'group_id',
            $groupId
        )
        ->with([

            'sender'
        ])
        ->latest()
        ->paginate(30);

        return response()->json([

            'status' => true,

            'messages' => $messages,
        ]);
    }

    /*
    =========================================
    ADD MEMBER
    =========================================
    */

    public function addMember(
        Request $request,
        $groupId
    ) {

        $request->validate([

            'user_id' =>

                'required|exists:users,id',
        ]);

        /*
        only admin
        */

        $admin = GroupMember::where(

            'group_id',
            $groupId

        )->where(

            'user_id',
            auth()->id()

        )->where(

            'is_admin',
            true

        )->exists();

        if (! $admin) {

            return response()->json([

                'status' => false,

                'message' => 'Only admin'
            ], 403);
        }

        /*
        already member
        */

        $exists = GroupMember::where(

            'group_id',
            $groupId

        )->where(

            'user_id',
            $request->user_id

        )->exists();

        if ($exists) {

            return response()->json([

                'status' => false,

                'message' => 'Already member'
            ]);
        }

        GroupMember::create([

            'group_id' =>

                $groupId,

            'user_id' =>

                $request->user_id,
        ]);

        return response()->json([

            'status' => true,

            'message' => 'Member added'
        ]);
    }

    /*
    =========================================
    REMOVE MEMBER
    =========================================
    */

    public function removeMember(
        $groupId,
        $userId
    ) {

        /*
        only admin
        */

        $admin = GroupMember::where(

            'group_id',
            $groupId

        )->where(

            'user_id',
            auth()->id()

        )->where(

            'is_admin',
            true

        )->exists();

        if (! $admin) {

            return response()->json([

                'status' => false,

                'message' => 'Only admin'
            ], 403);
        }

        GroupMember::where(

            'group_id',
            $groupId

        )->where(

            'user_id',
            $userId

        )->delete();

        return response()->json([

            'status' => true,

            'message' => 'Member removed'
        ]);
    }

    /*
    =========================================
    DELETE GROUP
    =========================================
    */

    public function delete($groupId)
    {
        $group = ChatGroup::where(
            'id',
            $groupId
        )
        ->where(
            'admin_id',
            auth()->id()
        )
        ->firstOrFail();

        $group->delete();

        return response()->json([

            'status' => true,

            'message' => 'Group deleted'
        ]);
    }
}