use axum::{routing::get, Router};

async fn hello() -> &'static str {
    "Hello, World!"
}

#[tokio::main(worker_threads = 1)]
async fn main() {
    let app = Router::new().route("/", get(hello));
    let listener = tokio::net::TcpListener::bind("0.0.0.0:5001").await.unwrap();
    println!("Tokio/Axum server started on http://0.0.0.0:5001");
    axum::serve(listener, app).await.unwrap();
}
