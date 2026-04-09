import Fastify from 'fastify'

const app = Fastify()

app.get('/', async () => 'Hello, World!')

await app.listen({ host: '0.0.0.0', port: 3000 })
console.log('Fastify server started on http://0.0.0.0:3000')
