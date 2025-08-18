# 📌 To-Do List API (Laravel 12)

API RESTful desenvolvida em **Laravel 12** para gerenciamento de tarefas, com autenticação via **JWT**.  

---

## 🌐 Base URL
```
http://localhost:1/api/v1
```

## 🔑 Autenticação
Após o login, inclua o token JWT nas requisições protegidas:
```
Authorization: Bearer {token}
```
Headers recomendados:
```
Content-Type: application/json
Accept: application/json
```

---

## 🧱 Envelope de resposta (padrão)
Todas as respostas (exceto 204) seguem o formato:
```json
{
  "success": true,
  "message": "string",
  "data": {},          // objeto, array ou string
  "errors": ["string"] // lista de mensagens de erro (quando houver)
}
```
> Em respostas de erro (4xx/5xx), **success = false** e `errors` pode conter detalhes de validação/negócio.

---

## 📚 Endpoints

### 1) Auth

#### 🔸 Registrar usuário
**POST** `/auth/register`

**Body (application/json)**
```json
{
  "name": "string",
  "email": "user@example.com",
  "password": "string"
}
```

**Resposta (201 – Created)**
```json
{
  "success": true,
  "message": "User created successfully",
  "data": {
    "id": 1,
    "name": "Leonardo",
    "email": "user@example.com"
  },
  "errors": []
}
```

**Erros comuns**
- **400 – Bad Request** (validação):
```json
{
  "success": false,
  "message": "Validation failed",
  "data": null,
  "errors": ["The email has already been taken."]
}
```
- **500 – Internal Server Error**:
```json
{
  "success": false,
  "message": "Internal error",
  "data": null,
  "errors": ["Unexpected error"]
}
```

---

#### 🔸 Login
**POST** `/auth/login`

**Body (application/json)**
```json
{
  "email": "user@example.com",
  "password": "string"
}
```

**Resposta (200 – OK)**
```json
{
  "success": true,
  "message": "Authenticated",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR..."
  },
  "errors": []
}
```

**Erros comuns**
- **400 – Bad Request** (credenciais ausentes ou inválidas):
```json
{
  "success": false,
  "message": "Invalid credentials",
  "data": null,
  "errors": ["Email or password is incorrect"]
}
```
- **500 – Internal Server Error** idem acima.

---

### 2) Task

#### 🔸 Listar tarefas
**GET** `/tasks`

**Resposta (200 – OK)**
```json
{
  "success": true,
  "message": "Tasks list",
  "data": [
    {
      "id": 1,
      "userId": 1,
      "title": "Estudar Laravel",
      "description": "Focar no módulo de autenticação",
      "totalPomodori": 4,
      "pomodoroValue": 25,
      "completedPomodori": 2,
      "status": "in_progress",
      "taskDate": "2025-08-18T13:59:46.744Z",
      "dueDate": "2025-08-20T13:59:46.744Z",
      "completedAt": null
    }
  ],
  "errors": []
}
```

**Erros**
- **400 – Bad Request**:
```json
{
  "success": false,
  "message": "Invalid query",
  "data": null,
  "errors": ["Invalid filter parameter"]
}
```
- **500 – Internal Server Error** idem.

---

#### 🔸 Criar tarefa
**POST** `/tasks`

**Body (application/json)**
```json
{
  "title": "string",
  "description": "string",
  "totalPomodori": 48,
  "pomodoroValue": 60,
  "taskDate": "2025-08-18T13:59:46.748Z",
  "dueDate": "2025-08-18T13:59:46.748Z"
}
```

**Resposta (201 – Created)**
```json
{
  "success": true,
  "message": "Task created successfully",
  "data": {
    "id": 1,
    "title": "string",
    "description": "string",
    "totalPomodori": 48,
    "pomodoroValue": 60,
    "completedPomodori": 0,
    "status": "pending",
    "taskDate": "2025-08-18T13:59:46.749Z",
    "dueDate": "2025-08-18T13:59:46.749Z",
    "assignedAt": "2025-08-18T13:59:46.749Z"
  },
  "errors": []
}
```

**Erros**
- **400 – Bad Request** (validação):
```json
{
  "success": false,
  "message": "Validation failed",
  "data": null,
  "errors": ["title is required", "totalPomodori must be an integer"]
}
```
- **500 – Internal Server Error** idem.

---

#### 🔸 Buscar tarefa por ID
**GET** `/tasks/{idTask}`

**Parâmetros de caminho**
- `idTask` (integer) – Obrigatório

**Resposta (200 – OK)**
```json
{
  "success": true,
  "message": "Task retrieved successfully",
  "data": {
    "id": 1,
    "userId": 1,
    "title": "string",
    "description": "string",
    "totalPomodori": 48,
    "pomodoroValue": 60,
    "completedPomodori": 0,
    "status": "pending",
    "taskDate": "2025-08-18T13:59:46.751Z",
    "dueDate": "2025-08-18T13:59:46.751Z",
    "completedAt": null
  },
  "errors": []
}
```

**Erros**
- **403 – Forbidden**:
```json
{
  "success": false,
  "message": "You don't have permission to access this task",
  "data": null,
  "errors": []
}
```
- **404 – Not Found**:
```json
{
  "success": false,
  "message": "Task not found",
  "data": null,
  "errors": []
}
```
- **500 – Internal Server Error** idem.

---

#### 🔸 Atualizar tarefa (PUT – substitui todos os campos)
**PUT** `/tasks/{idTask}`

**Parâmetros de caminho**
- `idTask` (integer) – Obrigatório

**Body (application/json)**  
> **Observação**: conforme a especificação recebida, o campo de request aparece como `completedPomodoro` (singular). As respostas usam `completedPomodori`.
```json
{
  "title": "string",
  "description": "stringstri",
  "totalPomodori": 48,
  "pomodoroValue": 60,
  "completedPomodoro": 0,
  "taskDate": "2025-08-18T13:59:46.755Z",
  "dueDate": "2025-08-18T13:59:46.755Z",
  "status": 0
}
```

**Resposta (200 – OK)**
```json
{
  "success": true,
  "message": "Task updated successfully",
  "data": {
    "id": 1,
    "userId": 1,
    "title": "string",
    "description": "string",
    "totalPomodori": 48,
    "pomodoroValue": 60,
    "completedPomodori": 0,
    "status": "in_progress",
    "taskDate": "2025-08-18T13:59:46.756Z",
    "dueDate": "2025-08-18T13:59:46.756Z",
    "completedAt": null
  },
  "errors": []
}
```

**Erros**
- **400 – Bad Request**, **403 – Forbidden**, **404 – Not Found**, **500 – Internal Server Error** (envelope de erro padrão).

---

#### 🔸 Atualização parcial (PATCH – atualiza campos enviados)
**PATCH** `/tasks/{idTask}`

**Parâmetros de caminho**
- `idTask` (integer) – Obrigatório

**Body (application/json)** (campos opcionais)
```json
{
  "title": "string",
  "description": "string",
  "totalPomodori": 0,
  "pomodoroValue": 0,
  "completedPomodori": 0,
  "taskDate": "2025-08-18T13:59:46.759Z",
  "dueDate": "2025-08-18T13:59:46.759Z",
  "status": 0
}
```

**Resposta (200 – OK)**
```json
{
  "success": true,
  "message": "Task partially updated successfully",
  "data": {
    "id": 1,
    "userId": 1,
    "title": "string",
    "description": "string",
    "totalPomodori": 48,
    "pomodoroValue": 60,
    "completedPomodori": 3,
    "status": "in_progress",
    "taskDate": "2025-08-18T13:59:46.760Z",
    "dueDate": "2025-08-18T13:59:46.760Z",
    "completedAt": null
  },
  "errors": []
}
```

**Erros**
- **400 – Bad Request** (ex.: *No valid fields* quando o payload não contém campos reconhecidos):
```json
{
  "success": false,
  "message": "No valid fields",
  "data": null,
  "errors": []
}
```
- **403 – Forbidden**, **404 – Not Found**, **500 – Internal Server Error** idem.

---

#### 🔸 Excluir tarefa
**DELETE** `/tasks/{idTask}`

**Parâmetros de caminho**
- `idTask` (integer) – Obrigatório

**Resposta (204 – No Content)**  
Sem corpo de resposta.

**Erros**
- **400 – Bad Request**, **403 – Forbidden**, **404 – Not Found**, **500 – Internal Server Error** (envelope de erro padrão).

---

## 📌 Esquema de objeto Task (referência)
```json
{
  "id": 0,
  "userId": 0,
  "title": "string",
  "description": "string",
  "totalPomodori": 0,
  "pomodoroValue": 0,
  "completedPomodori": 0,
  "status": "string",  // ex.: "pending", "in_progress", "done"
  "taskDate": "2025-08-18T13:59:46.744Z",
  "dueDate": "2025-08-18T13:59:46.744Z",
  "completedAt": "2025-08-18T13:59:46.744Z"
}
```

---

## 🧪 Dicas de testes rápidos (cURL)

**Login**
```bash
curl -X POST http://localhost:8000/api/v1/auth/login   -H "Content-Type: application/json"   -d '{"email":"user@example.com","password":"123456"}'
```

**Criar tarefa**
```bash
curl -X POST http://localhost:8000/api/v1/tasks   -H "Content-Type: application/json"   -H "Authorization: Bearer {TOKEN}"   -d '{"title":"Estudar Laravel","description":"JWT","totalPomodori":4,"pomodoroValue":25,"taskDate":"2025-08-18T13:59:46.748Z","dueDate":"2025-08-20T13:59:46.748Z"}'
```

**Atualização parcial (PATCH)**
```bash
curl -X PATCH http://localhost:8000/api/v1/tasks/1   -H "Content-Type: application/json"   -H "Authorization: Bearer {TOKEN}"   -d '{"completedPomodori":3,"status":1}'
```

