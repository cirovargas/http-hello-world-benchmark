FROM golang:1.24-alpine AS builder

WORKDIR /app
COPY main.go .
RUN go mod init bench && go build -o server .

FROM alpine:3.21

COPY --from=builder /app/server /server

EXPOSE 4000

CMD ["/server"]
