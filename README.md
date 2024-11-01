# Service Setup Guide

## Prerequisites
Ensure you have Docker and Docker Compose installed on your system.

## Step-by-Step Instructions

### 1. Start Docker Containers
Run the following command to start the Docker containers in detached mode:
```bash
docker-compose up -d
```
### 2. Generate Application Key
```
php artisan key:generate
```
### API Endpoint
```
Get User Transactions
To get user transactions, send a Get request to the following endpoint:

GET API http://localhost/api/v1/users
CURL Request:
curl --location 'http://localhost/api/v1/users?provider=DataProviderX&currency=EUR&balanceMin=220&statusCode=authorised'
