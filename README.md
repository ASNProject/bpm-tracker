<!--
 Copyright 2025 ariefsetyonugroho
 
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 
     https://www.apache.org/licenses/LICENSE-2.0
 
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
-->

# 📦 Vehicle Maps
Website monitoring bpm

## ✨ Features  
- API Endpoint (List BPM, Create BPM)


## ⚙️ Installation & 🚀 Usage 
##### Clone Project
```
git clone https://github.com/ASNProject/bpm-tracker.git
```
<b > Jika menggunakan xampp/ Windows, download file dan simpan di dalam C:/xampp/htdocs</b>

- Rename .env.example dengan .env dan sesuaikan pengaturan DB seperti dibawah
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_bpm
DB_USERNAME=root
DB_PASSWORD=
```

- Download database di folder ```sql``` dan import di mysql

##### Run Project
- Run Composer
```
composer update
```

- Run server
```
php artisan serve
```
- Development (For localhost)
```
php artisan serve --host=0.0.0.0 --port=8000
```

#### Route
##### BPM
- Post
```
Route : http://127.0.0.1:8000/api/bpm

Body: 
{
    "user_id": <string>,
    "age": <string>,
    "gender": <sting>,
    "status": <string>,
    "bpm": <string>,
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/bpm
```

##### BPMSingle
- Post
```
Route : http://127.0.0.1:8000/api/bpm-single

Body: 
{
    "bpm": <string>,
}
```
- Get 
```
Route : http://127.0.0.1:8000/api/bpm-single
```

## Notes
- Versi Larvel 12.0