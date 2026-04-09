FROM python:3.13-slim

RUN pip install --no-cache-dir fastapi granian

WORKDIR /app
COPY main.py .

EXPOSE 8000

CMD ["granian", "--interface", "asgi", "--host", "0.0.0.0", "--port", "8000", "--workers", "1", "main:app"]
