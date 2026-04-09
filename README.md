# HTTP Hello World Benchmark

A simple benchmark comparing HTTP server performance across five languages and frameworks, all running under identical resource constraints (1 CPU, 1 GB RAM) inside Docker containers.

---

## ⚠️ Warnings

> **I am not an expert in all the languages and frameworks used here.** The implementations may not represent the most performant configuration possible for each language. Default or common setups were used. Contributions and corrections are welcome.

> **This is not a real-world application benchmark.** A "Hello, World!" endpoint eliminates I/O, database queries, serialization, business logic, and other factors that dominate production workloads. Results here reflect raw HTTP handling throughput only. Your actual application performance will differ significantly depending on what the handler does.

---

## Stack

| Container | Language | Framework | Version | Port |
|---|---|---|---|---|
| swoole | PHP | Swoole HTTP Server | PHP 8.5.4 / Swoole 6.2.0 | 9501 |
| fastapi | Python | FastAPI + Uvicorn | Python 3.13 / FastAPI 0.135 | 8000 |
| fastify | JavaScript | Fastify | Node 23 / Fastify 5 | 3000 |
| golang | Go | net/http (stdlib) | Go 1.24 | 4000 |
| rust | Rust | Actix-web | Rust latest / Actix-web 4 | 5000 |

All containers: **1 CPU · 1 GB RAM · 1 worker**

Swoole is compiled with **io_uring enabled** (`--enable-swoole-uring`, liburing 2.8).

---

## Environment

- OS: Linux (WSL2 kernel 6.6.87)
- Tool: ApacheBench 2.3
- Command: `ab -n 1000000 -c 2000 http://localhost:<port>/`
- Requests: 1,000,000
- Concurrency: 2,000
- Each container was restarted immediately before its run to clear any residual CPU/memory state.

---

## Results

| Rank | Language | Framework | Req/sec | Mean latency | p50 | p95 | p99 | Failed |
|---|---|---|---|---|---|---|---|---|
| 🥇 1 | PHP | Swoole 6.2 (io_uring) | **15,951** | 125 ms | 124 ms | 141 ms | 149 ms | 0 |
| 🥈 2 | JavaScript | Fastify 5 | **14,433** | 138 ms | 135 ms | 148 ms | 165 ms | 0 |
| 🥉 3 | Rust | Actix-web 4 | **13,814** | 144 ms | 142 ms | 160 ms | 172 ms | 0 |
| 4 | Go | net/http | **13,737** | 145 ms | 144 ms | 159 ms | 168 ms | 0 |
| 5 | Python | FastAPI + Uvicorn | **5,258** | 380 ms | 374 ms | 427 ms | 491 ms | 0 |

---

## Raw ab Output

<details>
<summary>PHP — Swoole 6.2 (port 9501)</summary>

```
Concurrency Level:      2000
Time taken for tests:   62.689 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    15951.77 [#/sec] (mean)
Time per request:       125.378 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.8      0      23
Processing:    12  125  12.6    124     276
Waiting:        1  125  12.6    124     276
Total:         23  125  12.4    124     276

Percentage of the requests served within a certain time (ms)
  50%    124
  66%    128
  75%    130
  80%    132
  90%    138
  95%    141
  98%    144
  99%    149
 100%    276 (longest request)
```
</details>

<details>
<summary>JavaScript — Fastify 5 (port 3000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   69.285 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    14433.03 [#/sec] (mean)
Time per request:       138.571 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.7      0      20
Processing:    15  138  43.9    135    1141
Waiting:        3  138  43.9    135    1141
Total:         24  138  44.3    135    1150

Percentage of the requests served within a certain time (ms)
  50%    135
  66%    137
  75%    138
  80%    139
  90%    143
  95%    148
  98%    156
  99%    165
 100%   1150 (longest request)
```
</details>

<details>
<summary>Rust — Actix-web 4 (port 5000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   72.390 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    13814.13 [#/sec] (mean)
Time per request:       144.779 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.7      0     125
Processing:     9  145  13.3    142     299
Waiting:        0  145  13.3    142     299
Total:         20  145  13.1    142     299

Percentage of the requests served within a certain time (ms)
  50%    142
  66%    145
  75%    147
  80%    149
  90%    154
  95%    160
  98%    166
  99%    172
 100%    299 (longest request)
```
</details>

<details>
<summary>Go — net/http stdlib (port 4000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   72.798 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    13736.61 [#/sec] (mean)
Time per request:       145.596 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   2.2      0     127
Processing:    10  145  14.1    144     327
Waiting:        1  145  14.1    144     325
Total:         24  145  13.9    144     327

Percentage of the requests served within a certain time (ms)
  50%    144
  66%    147
  75%    149
  80%    151
  90%    155
  95%    159
  98%    164
  99%    168
 100%    327 (longest request)
```
</details>

<details>
<summary>Python — FastAPI + Uvicorn (port 8000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   190.178 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    5258.23 [#/sec] (mean)
Time per request:       380.356 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1   1.5      1     122
Processing:     9  379  29.5    373     549
Waiting:        1  322  37.2    322     521
Total:         22  380  29.3    374     550

Percentage of the requests served within a certain time (ms)
  50%    374
  66%    386
  75%    393
  80%    398
  90%    412
  95%    427
  98%    457
  99%    491
 100%    550 (longest request)
```
</details>

---

## Notes

- **Swoole** benefits from io_uring for async I/O at the kernel level, which likely contributes to its lead. It is also a compiled C extension running inside PHP, not interpreted userland code handling the event loop.
- **Fastify** is consistently one of the fastest Node.js frameworks and shows here, beating compiled languages in this specific test.
- **Rust and Go** are extremely close (~77 req/s apart). Both use 1 worker/goroutine and the stdlib or a minimal framework. With more workers both would scale further.
- **FastAPI + Uvicorn** with a single worker is limited by Python's single-threaded async event loop. Using Gunicorn with multiple Uvicorn workers, or switching to a faster ASGI server (e.g. `granian`), would significantly improve throughput.

## How to run

```bash
# Start all containers
docker compose up -d

# Run a benchmark against a specific service
ab -n 1000000 -c 2000 http://localhost:9501/   # Swoole
ab -n 1000000 -c 2000 http://localhost:8000/   # FastAPI
ab -n 1000000 -c 2000 http://localhost:3000/   # Fastify
ab -n 1000000 -c 2000 http://localhost:4000/   # Go
ab -n 1000000 -c 2000 http://localhost:5000/   # Rust
```
