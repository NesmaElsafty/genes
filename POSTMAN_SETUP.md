# Animal API Postman Collection Setup Guide

## Overview
This Postman collection provides comprehensive testing for the Animal API endpoints, including authentication, CRUD operations, filtering, and file uploads.

## Setup Instructions

### 1. Import the Collection
1. Open Postman
2. Click "Import" button
3. Select the `Animal_API_Collection.postman_collection.json` file
4. The collection will be imported with all endpoints

### 2. Configure Environment Variables
Before testing, set up the following variables in your Postman environment:

- **`base_url`**: Your API base URL (default: `http://localhost:8000`)
- **`auth_token`**: Will be automatically set after login

### 3. Authentication Setup
1. First, run the **"Login"** request in the Authentication folder
2. Copy the token from the response
3. Set the `auth_token` variable with the token value

## API Endpoints Overview

### Authentication
- **POST /api/login** - Authenticate user and get access token

### Animals
- **GET /api/animals** - Get all animals with filtering and pagination
- **GET /api/animals/{id}** - Get specific animal by ID
- **GET /api/animals/gender/{gender}** - Get animals by gender (male/female)
- **POST /api/animals** - Create new animal with images
- **PUT /api/animals/{id}** - Update existing animal
- **DELETE /api/animals/{id}** - Delete animal

## Request Parameters

### GET /api/animals Query Parameters
- `search` - Search term for animal_id, sir_id, dam_id, gender, farm name, event type, animal type, or breed
- `sorted_by` - Sorting options: `newest`, `oldest`, `name`
- `event_type_id` - Filter by event type ID
- `page` - Page number for pagination

### POST /api/animals Form Data
- `animal_id` (required) - Unique animal identifier
- `sir_id` (required) - Father's ID
- `dam_id` (required) - Mother's ID
- `birth_date` (required) - Animal birth date (YYYY-MM-DD)
- `gender` (required) - Gender: `male` or `female`
- `farm_id` (required) - Farm ID where animal belongs
- `event_type_id` (required) - Event type ID
- `breed_id` (required) - Animal breed ID
- `animal_type_id` (required) - Animal type ID
- `images` (required) - Animal images (jpeg, png, jpg, gif, svg, max 2MB each)

### PUT /api/animals/{id} Form Data
Same as POST but all fields are optional (use `sometimes` validation)

## Testing Workflow

### 1. Authentication Test
```bash
# Login to get token
POST {{base_url}}/api/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

### 2. Create Animal Test
```bash
# Create new animal with image
POST {{base_url}}/api/animals
Authorization: Bearer {{auth_token}}
Content-Type: multipart/form-data

Form Data:
- animal_id: ANM001
- sir_id: SIR001
- dam_id: DAM001
- birth_date: 2023-01-15
- gender: male
- farm_id: 1
- event_type_id: 1
- breed_id: 1
- animal_type_id: 1
- images: [file upload]
```

### 3. Get Animals with Filtering
```bash
# Get all animals with search and sorting
GET {{base_url}}/api/animals?search=ANM&sorted_by=newest&page=1
Authorization: Bearer {{auth_token}}

# Filter by event type
GET {{base_url}}/api/animals?event_type_id=1&sorted_by=oldest&page=1
Authorization: Bearer {{auth_token}}
```

### 4. Get Animals by Gender
```bash
# Get male animals
GET {{base_url}}/api/animals/gender/male
Authorization: Bearer {{auth_token}}

# Get female animals
GET {{base_url}}/api/animals/gender/female
Authorization: Bearer {{auth_token}}
```

## Expected Responses

### Success Response Format
```json
{
    "status": true,
    "message": "Animals fetched successfully",
    "data": [...],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total": 50,
        "last_page": 5
    }
}
```

### Error Response Format
```json
{
    "status": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

## Testing Scenarios

### 1. Admin vs Non-Admin Access
- **Admin users**: Can see all animals
- **Non-admin users**: Can only see animals from their assigned farms

### 2. Image Upload Testing
- Test with valid image formats (jpeg, png, jpg, gif, svg)
- Test file size limits (max 2MB)
- Test multiple image uploads

### 3. Search and Filter Testing
- Test search across multiple fields
- Test different sorting options
- Test event type filtering
- Test pagination

### 4. Validation Testing
- Test required field validation
- Test unique animal_id constraint
- Test foreign key constraints
- Test file type and size validation

## Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Check if auth_token is set correctly
   - Verify token hasn't expired
   - Re-login if needed

2. **422 Validation Error**
   - Check all required fields are provided
   - Verify file types and sizes
   - Check foreign key IDs exist

3. **404 Not Found**
   - Verify animal ID exists
   - Check if user has permission to access the animal

4. **500 Server Error**
   - Check server logs
   - Verify database connections
   - Check file upload permissions

### Debug Tips

1. **Check Response Headers** for additional error information
2. **Use Postman Console** to see detailed request/response logs
3. **Test with Minimal Data** first, then add complexity
4. **Verify Database State** after operations

## Collection Variables

The collection uses these variables:
- `{{base_url}}` - API base URL
- `{{auth_token}}` - Authentication token

To set these:
1. Click on the collection name
2. Go to "Variables" tab
3. Set the values
4. Save the collection

## File Upload Testing

For image upload testing:
1. Use the "Create Animal" or "Update Animal" requests
2. In the form-data section, click on the "images" field
3. Select "File" type
4. Choose your test image file
5. You can add multiple files by adding more "images" fields

## Security Notes

- Always use HTTPS in production
- Store tokens securely
- Don't commit tokens to version control
- Use environment-specific base URLs
- Test with different user roles

## Performance Testing

For load testing:
1. Use Postman's Collection Runner
2. Set iterations and delays
3. Monitor response times
4. Check for rate limiting
5. Test with large datasets

## Integration Testing

Test the complete workflow:
1. Login → Get token
2. Create animal → Get animal ID
3. Update animal → Verify changes
4. Search/filter → Verify results
5. Delete animal → Verify deletion 