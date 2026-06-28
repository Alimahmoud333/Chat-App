# Real-Time Chat Application API

A production-ready RESTful API built with Laravel for a modern real-time chat application. The project provides secure authentication, private messaging, group conversations, AI-powered chat, push notifications, role-based authorization, and real-time communication using Laravel Reverb.

The API follows RESTful architecture and includes complete authentication, user management, chat management, group management, administration features, and real-time broadcasting.

---

# Features

## Authentication

A complete authentication system built using Laravel Sanctum.

### Implemented Features

* User Registration
* User Login
* Secure Logout
* User Profile
* Update Profile
* Change Password
* Forgot Password
* Reset Password
* OTP Verification
* Resend OTP
* Secure API Authentication with Laravel Sanctum

### OTP Verification

OTP verification is implemented using Twilio Verify.

The verification system supports:

* Account Verification
* OTP Resend
* Forgot Password Verification
* Password Reset Verification

---

# User Profile

Authenticated users can:

* View Profile
* Update Profile Information
* Upload Profile Image
* Update Biography
* Update Phone Number
* Change Password
* Logout Securely

---

# Security

The application follows Laravel security best practices.

Implemented security features include:

* Laravel Sanctum Authentication
* Role-Based Authorization
* Middleware Protection
* Request Validation
* Password Hashing
* OTP Verification
* Protected API Routes
* Secure Access Tokens

---

# User Roles

The application supports two user roles.

## User

A regular user can:

* Send Private Messages
* Join Group Conversations
* Chat with the AI Assistant
* Manage Chat Settings
* Update Profile Information

## Admin

Administrators have additional privileges.

Admin features include:

* Dashboard
* User Management
* Ban Users
* Unban Users
* Delete Users
* View All Groups
* Delete Groups
* Remove Group Members
* Promote Group Members to Admin
* Delete Group Messages

---

# Private Chat

The application provides a complete one-to-one messaging system.

Features include:

* Send Messages
* Retrieve Conversations
* Conversation History
* Delete Messages
* Delete Messages for Everyone
* Search Messages
* Typing Indicator
* Message Reactions
* Seen Status
* Online Status
* Last Seen

---

# Group Chat

The application supports real-time group conversations.

Features include:

* Create Group
* View My Groups
* View Group Details
* Send Group Messages
* Retrieve Group Messages
* Add Members
* Remove Members
* Delete Groups

---

# Chat Settings

Each user has personalized chat preferences.

Supported settings include:

* Pin Chat
* Unpin Chat
* Archive Chat
* Unarchive Chat
* Mute Chat
* Unmute Chat
* Block User
* Unblock User

---

# AI Chat Assistant

The application integrates the OpenAI API to provide an AI assistant inside the chat application.

Features include:

* AI Chat Endpoint
* AI Message Generation
* Store User Messages
* Store AI Responses
* Real-Time AI Replies

---

# Push Notifications

Push notifications are implemented using Firebase Cloud Messaging (FCM).

Features include:

* Device Token Registration
* Firebase Admin SDK Integration
* Push Notifications
* Custom Firebase Notification Service

---

# Real-Time Communication

Real-time communication is powered by Laravel Reverb.

Implemented events include:

* Private Messages
* AI Message Broadcasting
* Typing Indicator
* Message Delivered
* Message Seen
* Online User Status

---

# REST API Modules

## Authentication

* Register
* Login
* Logout
* Verify OTP
* Resend OTP
* Forgot Password
* Reset Password
* Change Password

## User Profile

* Get Profile
* Update Profile

## Private Chat

* Get Users
* Get Conversations
* Get Conversation
* Send Message
* Delete Message
* Delete Message for Everyone
* Search Messages
* Typing Indicator
* React to Message

## Group Chat

* Create Group
* My Groups
* Group Details
* Send Group Message
* Retrieve Group Messages
* Add Member
* Remove Member
* Delete Group

## Chat Settings

* Pin Chat
* Unpin Chat
* Archive Chat
* Unarchive Chat
* Mute Chat
* Unmute Chat
* Block User
* Unblock User

## AI

* AI Chat

## Admin

* Dashboard
* User Management
* Group Management

---

# Technology Stack

## Backend

* Laravel
* PHP
* MySQL

## Authentication

* Laravel Sanctum
* Twilio Verify API

## Real-Time Communication

* Laravel Reverb
* Laravel Broadcasting
* Laravel Events
* Laravel Echo (Frontend)

## Push Notifications

* Firebase Cloud Messaging (FCM)
* Firebase Admin SDK

## Artificial Intelligence

* OpenAI API

---

# Project Structure

```text
app/
├── Events/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Models/
└── Providers/

bootstrap/
config/
database/
public/
resources/
routes/
storage/
```

---

# API Documentation

The repository includes an Apidog collection containing all available API endpoints.

Import the Apidog collection to test every endpoint of the application.

---

# Installation

Clone the repository.

```bash
git clone https://github.com/yourusername/chat-app-api.git

cd chat-app-api
```

Install PHP dependencies.

```bash
composer install
```

Create the environment file.

```bash
cp .env.example .env
```

Generate the application key.

```bash
php artisan key:generate
```

Create the storage symbolic link.

```bash
php artisan storage:link
```

Run database migrations.

```bash
php artisan migrate
```

---

# Required Packages

## Laravel Sanctum

Install Sanctum.

```bash
composer require laravel/sanctum
```

Publish Sanctum configuration.

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Run migrations.

```bash
php artisan migrate
```

---

## Twilio Verify

Install the Twilio PHP SDK.

```bash
composer require twilio/sdk
```

Configure Twilio inside your `.env` file.

```env
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_VERIFY_SERVICE=
```

---

## Laravel Reverb

Install Laravel Reverb.

```bash
composer require laravel/reverb
```

Install Reverb.

```bash
php artisan reverb:install
```

Start the Reverb server.

```bash
php artisan reverb:start
```

---

## Laravel Echo (Frontend)

Laravel Echo is required by the React frontend to receive real-time events.

```bash
npm install laravel-echo pusher-js
```

---

## Firebase Cloud Messaging

Install the Firebase package.

```bash
composer require kreait/laravel-firebase
```

Publish the configuration.

```bash
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider"
```

Add your Firebase service account JSON file and configure its path inside `.env`.

Example:

```env
FIREBASE_CREDENTIALS=/path/to/firebase_credentials.json
```

---

## OpenAI

Install the Laravel OpenAI package.

```bash
composer require openai-php/laravel
```

Publish the configuration.

```bash
php artisan vendor:publish --provider="OpenAI\Laravel\ServiceProvider"
```

Configure your API key.

```env
OPENAI_API_KEY=
```

---

# Environment Variables

Configure the following services inside the `.env` file.

```env
APP_NAME=
APP_ENV=
APP_KEY=
APP_DEBUG=true
APP_URL=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database

SANCTUM_STATEFUL_DOMAINS=

TWILIO_SID=
TWILIO_TOKEN=
TWILIO_VERIFY_SERVICE=

OPENAI_API_KEY=

FIREBASE_CREDENTIALS=

REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=
REVERB_PORT=
REVERB_SCHEME=http
```

---

# Running the Application

Start the Laravel server.

```bash
php artisan serve
```

Start the Reverb server.

```bash
php artisan reverb:start
```

Start the queue worker.

```bash
php artisan queue:work
```

---

# API Testing

The repository contains a complete Apidog collection for testing all available API endpoints.

Simply import the collection into Apidog and start testing.

---

# Future Improvements

* Voice Messages
* File Sharing
* Video Calls
* End-to-End Encryption
* Message Editing
* Message Forwarding
* Stories
* Multi-Device Synchronization

---

# Author

Ali Mahmoud


