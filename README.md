# Payment Orchestration

A modular Symfony-based application that orchestrates card payments across multiple gateways (ACI, Shift4, etc.). Designed with Clean Architecture (DDD) principles: `Domain`, `Application`, `Infrastructure`, and `UI`.

---

## 🧱 Tech Stack

- PHP 8.2
- Symfony 6+
- Docker + Docker Compose
- PHPUnit
- Guzzle (for HTTP API abstraction)

---

## 📁 Folder Structure

<pre>
src/ 
└── Modules/ 
    └── PaymentOrchestration/ 
        ├── Domain/ # Pure business logic: entities, value objects, interfaces (ports) 
        │   └── Model/ # Aggregates, Entities, Value Objects
        │
        ├── Application/ # Use cases, input/output ports, DTOs 
        │   ├── UseCase/ # Application services (e.g., PayWithCardUseCase) 
        │   └── Port/ 
        │       └── Outgoing/ # PresenterInterface, GatewayProvider, etc.
        │ 
        ├── Infrastructure/ # External world implementations 
        │   ├── Api/ # Guzzle clients, Result, ApiServiceConfig 
        │   ├── Config/ # Env providers for API credentials 
        │   ├── Gateway/ # Gateway implementations (e.g., Aci, Shift4) 
        │   └── Presenter/ # DelegatingPaymentPresenter 
        │ 
        └── UI/ # Delivery layer 
            ├── Http/ 
            │   ├── Controller/ # Symfony HTTP controllers 
            │   ├── Request/ # HTTP DTOs (e.g., PaymentRequest) 
            │   ├── Resolver/ # Symfony argument resolvers 
            │   └── Presenter/ # JsonPaymentPresenter 
            │ 
            └── Cli/ 
                ├── Command/ # Symfony Console Commands 
                └── Presenter/ # CliPaymentPresenter (uses SymfonyStyle) </pre>

---

## ⚙️ Installation

```bash
git clone https://github.com/beloofficial/ddd-integration.git

cd ddd-integration

docker-compose up -d --build
```

## 🌐 HTTP Usage

### Endpoint

`POST /app/example/{gateway}`

- Replace `{gateway}` with a supported payment gateway alias:
    - `aci`
    - `shift4`

### Request

```bash
curl --location 'http://localhost:8080/app/example/aci' \
--header 'Accept: application/json' \
--form 'amount="500"' \
--form 'currency="EUR"' \
--form 'card="4200000000000000"' \
--form 'expMonth="05"' \
--form 'expYear="2034"' \
--form 'cvv="123"'
```

### Success Response

```json
{
  "status": "success",
  "transactionId": "char_eNRr1kKgzrKJR9y7pHsonGwg",
  "createdAt": "2025-06-30 14:42:53",
  "amount": 500,
  "currency": "EUR",
  "cardBin": "420000"
}
```

### Error Response

```json
{
  "status": "error",
  "message": "Failed to send pay request to ACI PS: invalid or missing parameter"
}
```

## 🖥️ CLI Usage

### Command

```bash
docker exec -it php bash

bin/console app:pay aci \
    --amount=1000 \
    --currency=EUR \
    --card=4200000000000000 \
    --exp-month=12 \
    --exp-year=2026 \
    --cvv=123    
```

```bash
docker exec -it php bash

bin/console app:pay shift4 \
    --amount=1000 \
    --currency=EUR \
    --card=4200000000000000 \
    --exp-month=12 \
    --exp-year=2026 \
    --cvv=123    
```

### Successful output

```text
✅ Payment processed
Transaction ID: char_eNRr1kKgzrKJR9y7pHsonGwg
Created At: 2025-06-30 14:42:53
Amount: 1000
Currency EUR
Card BIN: 420000
```

## 🧪 Run tests

- Contains 1 functional test class and 2 unit test classes:
    - Functional: `PayCommandTest`
    - Unit: `PayWithCardUseCaseTest` and `AciGatewayTest`

### Command

```bash
docker exec -it php bash

vendor/bin/phpunit
```

