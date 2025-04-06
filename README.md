# Music Vote Tracking

**Music Vote Tracking** is a web application that allows users to vote on their favorite music albums. It tracks the number of upvotes and downvotes for each album. This application is built using **Laravel** for the backend, **MySQL** for the database, and **Vue.js** with **Inertia.js** for the frontend.

## Features

- **User Authentication:** Users can log in to vote on albums.
- **Album Voting:** Users can upvote or downvote albums.
- **View Votes:** Track the number of upvotes and downvotes for each album.
- **Pagination:** Albums are paginated for easier navigation.
- **Admin Panel:** Admin users can delete albums and manage content.
- **Soft Deletes:** Albums are soft-deleted for recovery.

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Vue.js 3 with TypeScript
- **Database:** MySQL
- **API:** Inertia.js for seamless client-side navigation
- **Database ORM:** Eloquent ORM for managing MySQL database interactions

## Prerequisites

Ensure the following are installed on your machine:

- **PHP** (>= 8.1)
- **Composer** (Dependency Manager for PHP)
- **Node.js** (>= 16.x)
- **NPM/Yarn** (Package manager for JavaScript)
- **MySQL** (>= 5.7)
- **Git** (Version control system)

### Admin Credentials

The default admin credentials for the backend application are:

- **Email**: superadmin@gmail.com
- **Password**: superadmin

## Installation

### Step 1: Clone the Repository

Clone the repository to your local machine:
```bash
git clone https://github.com/yourusername/music-vote-tracking.git
cd music-vote-tracking
```

### Step 2: Set Up Environment

Copy the .env.example file to .env and update the database credentials:
```bash
cp .env.example .env
```

Configure your .env file with the correct MySQL credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Vote Tracks
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Install Backend Dependencies

Run the following command to install PHP dependencies using Composer:
```bash
composer install
```

### Step 4: Install Frontend Dependencies

Navigate to the frontend directory and install the required JavaScript dependencies:
```bash
npm install
```

### Step 5: Generate Application Key

Run the following command to generate your application key:
```bash
php artisan key:generate
```

### Step 6: Initialize the application 

Run the following Artisan command to initialize the application:
```bash
php artisan app:initialize
```

### Step 7: Run the application 

Run the following command to start the application:
```bash
composer run dev
```

### Step 8: Visit application in the browser

http://127.0.0.1:8000/