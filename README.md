# Holded Service (Vending Machine API)

This service is an API developed in **PHP with Symfony** that implements the business logic to simulate the behavior of
a **vending machine**. The project is structured under the principles of **Hexagonal Architecture (DDD)**.

## 🚀 Prerequisites

Before starting the installation, make sure you have the following tools installed in your development environment:

* [Docker](https://www.docker.com/) and **Docker Compose**
* [Make](https://www.gnu.org/software/make/) (installed by default on most macOS/Linux systems. On Windows, using WSL2
  is recommended).

---

## ⚙️ Quick Start Guide (Makefile Usage)

The project includes a `Makefile` that automates the entire workflow with Docker. Follow these steps in order to spin up
the environment:

### 1. Build the container

If this is your first time initializing the project or if you have modified infrastructure configurations, compile the
Docker images:

## 🛠️ Available Commands (Makefile)

The project features a `Makefile` to quickly interact with the Docker container from the terminal. The available
commands are detailed below:

| Command                     | Description                                                                   |
|:----------------------------|:------------------------------------------------------------------------------|
| **`make build`**            | Compiles and builds the project's Docker images from scratch.                 |
| **`make up`**               | Starts the containers in the background (*detached mode*).                    |
| **`make composer-install`** | Instals PHP dependencies (`vendor/`) inside the container using Composer.     |
| **`make logs`**             | Displays the service logs in real time to monitor execution and errors.       |
| **`make ssh`**              | Opens an interactive `bash` terminal inside the microservice container.       |
| **`make composer-update`**  | Updates Composer packages according to the rules in the `composer.json` file. |
| **`make down`**             | Stops and removes the project's containers, networks, and local volumes.      |

### Recommended Basic Workflow:

1. To start the project for the first time: `make build` ➡️ `make up` ➡️ `make composer-install`.
2. For day-to-day development: `make up`.
3. To shut down the environment when finished: `make down`.

## Endpoints

1. List Products Vending

```bash
   curl --location --request GET 'http://localhost:8080/api/vending/products' \
   --header 'Content-Type: application/json' \
   --header 'Cookie: PHPSESSID=dd1ec5433eab78974d1d86e98c37c9fc'
```

2. Insert Coin to the machine

```bash
   curl --location 'http://localhost:8080/api/vending/insert-coin' \
   --header 'Content-Type: application/json' \
   --header 'Cookie: PHPSESSID=dd1ec5433eab78974d1d86e98c37c9fc' \
   --data '{
   "data":{
   "coin": 2.00
   }

}'
```

3. Select product

```bash
   curl --location 'http://localhost:8080/api/vending/products/buy' \
   --header 'Content-Type: application/json' \
   --header 'Cookie: PHPSESSID=dd1ec5433eab78974d1d86e98c37c9fc' \
   --data '{
   "data":{
   "product_key": "COKE"
   }

}'
```

### 4. Refund Coins

```bash
curl --location --request POST 'http://localhost:8080/api/vending/refund' \
--header 'Cookie: PHPSESSID=dd1ec5433eab78974d1d86e98c37c9fc' \
--data ''