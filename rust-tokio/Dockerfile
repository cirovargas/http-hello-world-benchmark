FROM rust:alpine AS builder

RUN apk add --no-cache musl-dev

WORKDIR /app
COPY Cargo.toml .
COPY src/ src/

RUN cargo build --release

FROM alpine:3.21

COPY --from=builder /app/target/release/bench /server

EXPOSE 5001

CMD ["/server"]
