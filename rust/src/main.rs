use actix_web::{get, App, HttpServer, Responder};

#[get("/")]
async fn hello() -> impl Responder {
    "Hello, World!"
}

#[actix_web::main]
async fn main() -> std::io::Result<()> {
    println!("Rust/Actix-web server started on http://0.0.0.0:5000");
    HttpServer::new(|| App::new().service(hello))
        .bind(("0.0.0.0", 5000))?
        .workers(1)
        .run()
        .await
}
