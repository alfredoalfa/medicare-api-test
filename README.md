
# EndPoins List

## Login method POST
http://127.0.0.1:8000/api/login.php

```
{"username":"acontreras", "password":"123456"} /// doctor
{"username":"lparra", "password":"123456"} // paciente
```

## List visits method GET   
http://127.0.0.1:8000/api/list_visits.php


## Create visit method POST
http://127.0.0.1:8000/api/create_visit.php

```
{
    "id_doctor":"2",
    "specialization":"internal Medicine",
    "date":"2022-01-16"
}
```

## Confirm visit method POST
http://127.0.0.1:8000/api/confirm_appointment.php
```
{
      "id":"8",
      "id_patient": "1",
      "id_doctor": "2",
      "id_status_visit": "2",
      "specialization": "internista",
      "today_visits": "2022-01-17"
}
```
