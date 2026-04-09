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
| 🥇 1 | PHP | Swoole 6.2 (io_uring) | **16,600** | 120 ms | 115 ms | 135 ms | 153 ms | 0 |
| 🥈 2 | Rust | Actix-web 4 | **16,368** | 122 ms | 118 ms | 129 ms | 147 ms | 0 |
| 🥉 3 | JavaScript | Fastify 5 | **15,685** | 127 ms | 119 ms | 135 ms | 378 ms | 0 |
| 4 | Go | net/http | **15,680** | 127 ms | 124 ms | 138 ms | 151 ms | 0 |
| 5 | Python | FastAPI + Uvicorn | **5,409** | 369 ms | 359 ms | 389 ms | 821 ms | 0 |

---

## Raw ab Output

<details>
<summary>PHP — Swoole 6.2 (port 9501)</summary>

```
Concurrency Level:      2000
Time taken for tests:   60.241 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    16599.99 [#/sec] (mean)
Time per request:       120.482 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.7      0      21
Processing:    10  120  34.9    115     590
Waiting:        1  120  34.9    115     590
Total:         21  120  34.8    115     590

Percentage of the requests served within a certain time (ms)
  50%    115
  66%    119
  75%    122
  80%    123
  90%    129
  95%    135
  98%    143
  99%    153
 100%    590 (longest request)
```
</details>

<details>
<summary>Rust — Actix-web 4 (port 5000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   61.097 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    16367.52 [#/sec] (mean)
Time per request:       122.193 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.8      0     475
Processing:     8  122  34.5    118     602
Waiting:        1  122  34.5    118     602
Total:         21  122  34.4    118     602

Percentage of the requests served within a certain time (ms)
  50%    118
  66%    119
  75%    120
  80%    121
  90%    125
  95%    129
  98%    140
  99%    147
 100%    602 (longest request)
```
</details>

<details>
<summary>JavaScript — Fastify 5 (port 3000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   63.757 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    15684.62 [#/sec] (mean)
Time per request:       127.513 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.9      0     470
Processing:    15  127  71.1    119    1131
Waiting:        6  127  71.1    119    1131
Total:         24  127  71.3    119    1136

Percentage of the requests served within a certain time (ms)
  50%    119
  66%    120
  75%    121
  80%    122
  90%    127
  95%    135
  98%    146
  99%    378
 100%   1136 (longest request)
```
</details>

<details>
<summary>Go — net/http stdlib (port 4000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   63.777 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    15679.63 [#/sec] (mean)
Time per request:       127.554 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   1.0      0     472
Processing:     8  127  36.1    124     608
Waiting:        1  127  36.1    124     607
Total:         20  127  36.0    124     608

Percentage of the requests served within a certain time (ms)
  50%    124
  66%    126
  75%    128
  80%    129
  90%    134
  95%    138
  98%    144
  99%    151
 100%    608 (longest request)
```
</details>

<details>
<summary>Python — FastAPI + Uvicorn (port 8000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   184.861 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    5409.48 [#/sec] (mean)
Time per request:       369.722 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1   1.1      1      19
Processing:     8  368  71.3    358    1185
Waiting:        1  315  65.2    310    1168
Total:         20  369  71.2    359    1185

Percentage of the requests served within a certain time (ms)
  50%    359
  66%    364
  75%    366
  80%    369
  90%    377
  95%    389
  98%    459
  99%    821
 100%   1185 (longest request)
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
