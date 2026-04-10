# HTTP Hello World Benchmark Report

**Date:** 2026-04-10
**Tool:** bombardier (Go-based HTTP benchmarking tool)
**Parameters:** 1,000,000 requests / 50 concurrent connections
**Each container was restarted immediately before its run.**

---

## System Specs

| Component | Details |
|-----------|---------|
| **CPU** | AMD Ryzen 9 7950X3D 16-Core (32 threads) @ 4.2 GHz base |
| **RAM** | 128 GB DDR5-4800 (4x 32 GB Kingston KF560C32-32) |
| **Motherboard** | ASUS ROG STRIX X670E-E GAMING WIFI |
| **GPU** | NVIDIA GeForce RTX 4090 (+ AMD Radeon integrated) |
| **Storage** | Samsung 980 PRO 2TB, Samsung 970 PRO 1TB, Sabrent Rocket 4.0 2TB, Crucial P3 4TB |
| **OS** | Windows 10 Pro 10.0.19045 (64-bit) |
| **Docker** | Docker Desktop 29.0.1 (WSL2 backend) |

---

## Stack

| Container | Language | Framework | Port |
|-----------|----------|-----------|------|
| swoole | PHP 8.5 | Swoole 6.2 (io_uring) | 9501 |
| rust | Rust | Actix-web 4 | 5000 |
| rust-tokio | Rust | Axum 0.8 (Tokio/Hyper) | 5001 |
| fastify | JavaScript | Fastify 5 (Node 23) | 3000 |
| golang | Go 1.24 | net/http (stdlib) | 4000 |
| java | Java 21 | Spring Boot 3.4 WebFlux (Netty) | 8080 |
| java-native | Java 21 | Spring Boot 3.4 + GraalVM Native | 8081 |
| fastapi | Python 3.13 | FastAPI 0.135 + Granian 2.7 | 8000 |

All containers limited to **1 CPU / 1 GB RAM**.

---

## Results

| Rank | Language | Framework | Req/sec | Avg Latency | Max Latency | Stdev | Throughput | Failed |
|------|----------|-----------|---------|-------------|-------------|-------|------------|--------|
| 1 | PHP | Swoole 6.2 (io_uring) | **43,299** | 1.15ms | 17.77ms | 109.93us | 9.50 MB/s | 0 |
| 2 | Rust | Axum (Tokio) | **42,171** | 1.18ms | 17.58ms | 113.33us | 7.72 MB/s | 0 |
| 3 | Rust | Actix-web 4 | **41,407** | 1.20ms | 68.64ms | 121.97us | 7.58 MB/s | 0 |
| 4 | JavaScript | Fastify 5 | **25,262** | 1.98ms | 170.70ms | 740.39us | 5.78 MB/s | 0 |
| 5 | Go | net/http (stdlib) | **11,814** | 4.23ms | 79.70ms | 4.91ms | 2.16 MB/s | 0 |
| 6 | Java | Spring Boot WebFlux (JVM) | **10,394** | 4.81ms | 390.64ms | 6.13ms | 1.53 MB/s | 0 |
| 7 | Java | Spring Boot + GraalVM Native | **8,400** | 5.95ms | 112.06ms | 6.84ms | 1.23 MB/s | 0 |
| 8 | Python | FastAPI + Granian | **2,732** | 18.30ms | 153.39ms | 1.22ms | 539 KB/s | 0 |

---

## Raw bombardier Output

### 1. PHP -- Swoole 6.2 (port 9501)

```
Statistics        Avg      Stdev        Max
  Reqs/sec     43298.53    5767.11   52897.36
  Latency        1.15ms   109.93us    17.77ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:     9.50MB/s
```

### 2. Rust -- Axum / Tokio (port 5001)

```
Statistics        Avg      Stdev        Max
  Reqs/sec     42170.97    5599.65   50953.31
  Latency        1.18ms   113.33us    17.58ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:     7.72MB/s
```

### 3. Rust -- Actix-web 4 (port 5000)

```
Statistics        Avg      Stdev        Max
  Reqs/sec     41407.16    4872.58   47749.28
  Latency        1.20ms   121.97us    68.64ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:     7.58MB/s
```

### 4. JavaScript -- Fastify 5 (port 3000)

```
Statistics        Avg      Stdev        Max
  Reqs/sec     25262.04    4920.45   38598.07
  Latency        1.98ms   740.39us   170.70ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:     5.78MB/s
```

### 5. Go -- net/http stdlib (port 4000)

```
Statistics        Avg      Stdev        Max
  Reqs/sec     11813.68   12923.21   50950.00
  Latency        4.23ms     4.91ms    79.70ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:     2.16MB/s
```

### 6. Java -- Spring Boot WebFlux / Netty (port 8080)

```
Statistics        Avg      Stdev        Max
  Reqs/sec     10393.49    2619.90   17747.12
  Latency        4.81ms     6.13ms   390.64ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:     1.53MB/s
```

### 7. Java -- Spring Boot + GraalVM Native (port 8081)

```
Statistics        Avg      Stdev        Max
  Reqs/sec      8399.59    9555.02   38298.66
  Latency        5.95ms     6.84ms   112.06ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:     1.23MB/s
```

### 8. Python -- FastAPI + Granian (port 8000)

```
Statistics        Avg      Stdev        Max
  Reqs/sec      2732.19     210.94    4550.00
  Latency       18.30ms     1.22ms   153.39ms
  HTTP codes:
    1xx - 0, 2xx - 1000000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:   538.87KB/s
```

---

## Notes

- **Swoole** takes the crown at ~43.3k req/s. The io_uring integration and C-level event loop give it a significant edge in raw throughput.
- **Rust (Axum and Actix-web)** are nearly tied at ~41-42k req/s, both with sub-1.2ms average latency. Axum slightly edges out Actix-web this time -- the opposite of the previous `ab` benchmark with 2000 connections, suggesting Axum performs better under moderate concurrency.
- **Fastify** delivers ~25k req/s -- impressive for single-threaded Node.js, sitting at ~60% of the top performers.
- **Go** at ~11.8k req/s is notably lower than expected. The 1 CPU limit in Docker constrains Go's goroutine scheduler which normally benefits from multiple cores. The high stdev (12.9k) suggests inconsistent performance under this constraint.
- **Java JVM** (~10.4k) outperforms **GraalVM Native** (~8.4k) in sustained throughput -- the JIT compiler warms up and optimizes hot paths over 1M requests, while GraalVM's AOT compilation can't adapt at runtime. The JVM's max latency (390ms) reflects GC pauses that GraalVM avoids (112ms max).
- **FastAPI + Granian** at ~2.7k req/s is the slowest but extremely consistent (stdev of only 210 req/s). Python's GIL and interpreter overhead are the bottleneck despite Granian's Rust-based server.
- **Important caveat:** bombardier ran on the same host as Docker (Windows 10 + WSL2), so all results include overhead from the Docker networking layer and host CPU contention. Numbers are relative, not absolute.
