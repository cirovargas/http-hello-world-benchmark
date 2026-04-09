FROM python:3.13-slim

RUN pip install --no-cache-dir fastapi uvicorn

WORKDIR /app
COPY main.py .

EXPOSE 8000

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000", "--workers", "1"]
