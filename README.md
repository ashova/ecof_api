# Temperature Sensor Management Application

This is a simple PHP application for managing data from temperature sensors. It includes endpoints for receiving temperature readings from sensors and simulating sensor readings.

## Requirements

- PHP 8.1 or higher
- Docker and Docker Compose
- Composer
---

## Getting Started

### 1. Clone the repository and navigate into it
```bash
git clone https://your-repository-url.git
cd your-repository-name 
```

### 2. Build the Docker Image
```bash
docker-compose build
```

### 3. Start the Docker Containers

```bash
docker-compose up -d
```
### 4. Create Database and Tables
```bash
docker exec -i mysql_db mysql -u root -proot ecof_db < database/migrations/create_tables.sql
```


### 5. Run Database Seeder
To populate your database with initial data, run the following command:
```bash
docker exec -it php_app php /app/public/run_seeder.php
```

### 6. Access the Application
```
http://localhost:8000
```
---
## API Endpoints
1. Push Sensor Reading
   - Endpoint: `POST /api/push`
   - Request Body:
   ```
   {
     "reading": {
       "sensor_uuid": "unique uuid of sensor",
       "temperature": "decimal format, xxx.xx, in celsius"
     }
   }
   ```

2. Simulate Sensor Read 
   - Endpoint: `GET /sensor/read/%sensor_ip%`
   - Response: CSV string containing reading_id and temperature.


3. Average Temperature
   - Endpoint: `GET /api/average-temperature?days=X`
   - Description: Returns average temperature from all sensors during the last X days.


4. Average Temperature for Specific Sensor
   - Endpoint: `GET /api/sensor/average?uuid=sensor_uuid`
   - Description: Returns average temperature for a specific sensor in the last hour.