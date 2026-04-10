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
| fastapi | Python | FastAPI + Granian | Python 3.13 / FastAPI 0.135 / Granian 2.7 | 8000 |
| fastify | JavaScript | Fastify | Node 23 / Fastify 5 | 3000 |
| golang | Go | net/http (stdlib) | Go 1.24 | 4000 |
| rust | Rust | Actix-web | Rust latest / Actix-web 4 | 5000 |
| rust-tokio | Rust | Axum (Tokio + Hyper) | Rust latest / Axum 0.8 / Tokio 1 | 5001 |
| java | Java | Spring Boot WebFlux (Netty) | Java 21 / Spring Boot 3.4 | 8080 |
| java-native | Java | Spring Boot WebFlux + GraalVM Native | Java 21 / Spring Boot 3.4 / GraalVM CE 21 | 8081 |

All containers: **1 CPU · 1 GB RAM · 1 worker** (Java/Java-native use default Netty event loop threads)

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
| 5 | Python | FastAPI + Granian | **14,613** | 137 ms | 119 ms | 169 ms | 191 ms | 0 |
| 6 | Java | Spring Boot WebFlux + GraalVM Native | **14,452** | 138 ms | 113 ms | 185 ms | 199 ms | 0 |
| 7 | Rust | Axum (Tokio) | **14,087** | 142 ms | 116 ms | 142 ms | 1,129 ms | 0 |
| 8 | Java | Spring Boot WebFlux (JVM) | **13,116** | 152 ms | 134 ms | 200 ms | 794 ms | 0 |

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
<summary>Python — FastAPI + Granian (port 8000)</summary>

```
Concurrency Level:      2000
Time taken for tests:   68.433 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    14612.83 [#/sec] (mean)
Time per request:       136.866 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   1.2      0     394
Processing:    26  137  66.5    119    1116
Waiting:        1  136  66.5    119    1116
Total:         26  137  66.6    119    1125

Percentage of the requests served within a certain time (ms)
  50%    119
  66%    126
  75%    159
  80%    161
  90%    166
  95%    169
  98%    175
  99%    191
 100%   1125 (longest request)
```
</details>

<details>
<summary>Rust — Axum / Tokio (port 5001)</summary>

```
Concurrency Level:      2000
Time taken for tests:   70.988 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    14086.81 [#/sec] (mean)
Time per request:       141.977 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   1.3      0     913
Processing:    11  142 145.1    116    1173
Waiting:        0  142 145.1    116    1173
Total:         28  142 145.3    116    1173

Percentage of the requests served within a certain time (ms)
  50%    116
  66%    119
  75%    121
  80%    123
  90%    129
  95%    142
  98%   1018
  99%   1129
 100%   1173 (longest request)
```
</details>

<details>
<summary>Java — Spring Boot WebFlux / Netty (port 8080)</summary>

```
Concurrency Level:      2000
Time taken for tests:   76.242 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    13116.08 [#/sec] (mean)
Time per request:       152.485 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1   1.8      0     659
Processing:    12  152 117.4    134    2331
Waiting:        4  151 117.4    134    2331
Total:         25  152 117.7    134    2331

Percentage of the requests served within a certain time (ms)
  50%    134
  66%    142
  75%    148
  80%    151
  90%    161
  95%    200
  98%    401
  99%    794
 100%   2331 (longest request)
```
</details>

<details>
<summary>Java — Spring Boot WebFlux + GraalVM Native (port 8081)</summary>

```
Concurrency Level:      2000
Time taken for tests:   69.195 seconds
Complete requests:      1000000
Failed requests:        0
Requests per second:    14451.97 [#/sec] (mean)
Time per request:       138.389 [ms] (mean)

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    2   2.0      2      25
Processing:    10  136  81.7    111    1163
Waiting:        3  135  81.7    110    1161
Total:         28  138  81.3    113    1165

Percentage of the requests served within a certain time (ms)
  50%    113
  66%    124
  75%    172
  80%    175
  90%    181
  95%    185
  98%    190
  99%    199
 100%   1165 (longest request)
```
</details>

---

## Notes

- **Swoole** and **Rust** are essentially tied at the top (~232 req/s apart). Swoole benefits from io_uring for async I/O at the kernel level and is a compiled C extension driving the event loop, not interpreted PHP.
- **Fastify and Go** are also nearly identical (~5 req/s apart), both sitting just below the top two.
- **FastAPI + Granian** with a single worker delivers ~14,600 req/s — a ~2.7× improvement over Uvicorn (5,409 req/s). Granian is a Rust-based ASGI server that avoids much of Uvicorn's Python overhead. Python is now within 15% of Go/Fastify.
- **Axum (Tokio)** reaches ~14,100 req/s with good median (116 ms) and p95 (142 ms), but p99 spikes to 1,129 ms under sustained 2,000-connection load with a single worker thread. Actix-web handles the same conditions with a p99 of just 147 ms, suggesting Actix-web's internal dispatcher handles connection backpressure more gracefully.
- **Spring Boot WebFlux + GraalVM Native** (AOT-compiled) reaches ~14,450 req/s — a ~10% improvement over the JVM version, but more importantly the p99 tail drops from 794 ms to 199 ms, eliminating GC pause spikes entirely. Compile time is ~46s.
- **Spring Boot WebFlux (JVM)** lands at ~13,100 req/s. GC pauses are visible in the p99 tail (794 ms). The default Netty event loop uses multiple threads despite the 1 CPU limit.

## How to run

```bash
docker compose up -d

ab -n 1000000 -c 2000 http://localhost:9501/   # Swoole
ab -n 1000000 -c 2000 http://localhost:8000/   # FastAPI
ab -n 1000000 -c 2000 http://localhost:3000/   # Fastify
ab -n 1000000 -c 2000 http://localhost:4000/   # Go
ab -n 1000000 -c 2000 http://localhost:5000/   # Rust (Actix-web)
ab -n 1000000 -c 2000 http://localhost:5001/   # Rust (Axum/Tokio)
ab -n 1000000 -c 2000 http://localhost:8080/   # Java (JVM)
ab -n 1000000 -c 2000 http://localhost:8081/   # Java (Native)
```
