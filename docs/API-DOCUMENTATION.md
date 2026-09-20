# CareerConnect - AJAX Endpoint API Documentation

## 1. Real-Time Email Check
- **Endpoint**: `GET /ajax/check-email.php`
- **Parameters**: `email` (string)
- **Response**: `{"exists": true|false}`

---

## 2. Real-Time Username Check
- **Endpoint**: `GET /ajax/check-username.php`
- **Parameters**: `username` (string)
- **Response**: `{"exists": true|false}`

---

## 3. Live Job Filter & Search
- **Endpoint**: `GET /ajax/filter-jobs.php`
- **Parameters**: `q` (keyword), `category` (int), `type` (string), `experience` (string), `page` (int)
- **Response**:
```json
{
  "success": true,
  "html": "<div class='card-saas'>...</div>",
  "pagination": "<nav>...</nav>",
  "total": 12
}
```

---

## 4. Bookmark / Save Job
- **Endpoint**: `POST /ajax/save-job.php`
- **Parameters**: `job_id` (int)
- **Headers**: Requires active session
- **Response**:
```json
{
  "success": true,
  "action": "saved",
  "message": "Job bookmarked successfully!"
}
```
