# Knockout Zone - Combat Sports Management Platform

A comprehensive web application for managing combat sports events, fighter profiles, and community engagement.

## Table of Contents

- [Overview](#overview)
- [Main Functionalities](#main-functionalities)
- [Main Use Cases](#main-use-cases)
- [System Architecture](#system-architecture)
- [Application Flow](#application-flow)
- [Database Schema](#database-schema)
- [Installation & Setup](#installation--setup)
- [API Endpoints](#api-endpoints)
- [File Structure](#file-structure)

---

## Overview

Knockout Zone is a platform designed for:
- **Event Management**: Create, update, and manage combat sports events
- **User Management**: Register users, manage profiles, and handle authentication
- **Community Building**: Store, forum, and fighter information showcase
- **Admin Control**: Dedicated admin features for event and user management

---

## Main Functionalities

### 1. **User Management**
- User registration with email validation
- Admin registration with special privileges
- User login/logout with session management
- Profile management (update username, email, password)
- Profile picture upload and management
- Account deletion

### 2. **Event Management**
- Create new events with detailed information
- Upload event images
- Edit event details (title, date, location, description)
- Delete events (owner only)
- View all upcoming events with sorting by date
- Event filtering and search capabilities

### 3. **Content Management**
- Store section with merchandise listings
- Fighters directory with profiles
- Forum for community discussions
- About Us information page
- Home page with featured content

### 4. **Admin Features**
- Admin-only event management
- User management capabilities
- Profile picture management
- Event statistics and tracking

---

## Main Use Cases

```mermaid
graph TD
    A[User] -->|Register| B[Create Account]
    A -->|Login| C[Access Dashboard]
    C -->|View| D[Events]
    C -->|View| E[Fighters]
    C -->|Visit| F[Store]
    C -->|Participate| G[Forum]
    
    H[Admin] -->|Login| I[Admin Dashboard]
    I -->|Create| J[New Event]
    I -->|Manage| K[Users]
    I -->|Edit| L[Events]
    I -->|Monitor| M[System]
    
    B -->|Success| N[Profile Ready]
    J -->|Create| O[Event Active]
    D -->|Register| P[Buy Tickets]
```

### Use Case 1: New User Registration
```mermaid
sequenceDiagram
    participant User as User
    participant Web as Web App
    participant DB as Database
    
    User->>Web: Fill registration form
    Web->>Web: Validate inputs
    Web->>DB: Check if user exists
    DB-->>Web: User not found
    Web->>DB: Insert new user
    DB-->>Web: User created
    Web-->>User: Redirect to login
    User->>Web: Login with credentials
    Web->>DB: Authenticate user
    DB-->>Web: Valid credentials
    Web-->>User: Redirect to profile
```

### Use Case 2: Event Creation & Management
```mermaid
sequenceDiagram
    participant Admin as Admin
    participant Web as Web App
    participant DB as Database
    participant Storage as File Storage
    
    Admin->>Web: Fill event form
    Admin->>Web: Upload event image
    Web->>Storage: Save image file
    Storage-->>Web: Image saved
    Web->>DB: Validate event data
    Web->>DB: Insert event record
    DB-->>Web: Event created
    Web-->>Admin: Success confirmation
    Admin->>Web: View event list
    Web->>DB: Fetch events
    DB-->>Web: Return events
    Web-->>Admin: Display events
```

### Use Case 3: User Profile Management
```mermaid
sequenceDiagram
    participant User as User
    participant Web as Web App
    participant DB as Database
    
    User->>Web: Access profile page
    Web->>DB: Fetch user data
    DB-->>Web: User details
    Web-->>User: Display profile
    User->>Web: Update username/email
    Web->>DB: Check availability
    DB-->>Web: Available
    Web->>DB: Update user info
    DB-->>Web: Success
    Web-->>User: Changes saved
```

---

