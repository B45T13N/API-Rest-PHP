# Documentation API

## Routes

The following routes are available in this API:

| Route | Method | Description |
|-------|--------|-------------|
| /api/shop_list.php | GET | Retrieves a list of shops |
| /api/shop_list.php | POST | Retrieves a filtered list of shops by name |
| /api/shop_detail.php | GET | Retrieves a specific shop by ID |
| /api/delete_shop.php | DELETE | Deletes a specific shop by ID |
| /api/create_shop.php | POST | Creates a new shop |
| /api/update_shop.php | PUT | Updates an existing shop by ID |

## Parameters

### GET /api/shop_list.php

This route accepts no parameters.

### POST /api/shop_list.php

| Parameter | Type | Description |
|-----------|------|-------------|
| filter | string | The name filter to apply to the list of shops |

### GET /api/shop_detail.php

| Parameter | Type | Description |
|-----------|------|-------------|
| id | int | The ID of the shop to retrieve |

### DELETE /api/delete_shop.php

| Parameter | Type | Description |
|-----------|------|-------------|
| id | int | The ID of the shop to delete |

### POST /api/create_shop.php

| Parameter | Type | Description |
|-----------|------|-------------|
| name | string | The name of the shop to create |
| city | string | The city of the shop to create |

### PUT /api/update_shop.php

| Parameter | Type | Description |
|-----------|------|-------------|
| id | int | The ID of the shop to update |
| name | string | The new name for the shop |
| city | string | The new city for the shop |

## Response

All routes return a JSON-encoded response. The response has the following format:

```json
{
    "message": "Some message here",
    "data": { ... }
}
```

The `message` field contains a message describing the result of the API call, while the `data` field contains any additional data associated with the response. The `data` field may be omitted if no additional data is returned by the API call.

## Example

### Request

```http
POST /api/create_shop.php HTTP/1.1
Host: example.com
Content-Type: application/json

{
    "name": "My Shop",
    "city": "Paris"
}
```

### Response

```json
{
    "message": "This shop has been created"
}
```