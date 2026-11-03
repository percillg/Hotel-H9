const fs = require("fs");
const hotelDb = db.getSiblingDB("gestion_hotel_control2");

const int = { bsonType: "int" };
const money = { bsonType: "decimal", minimum: NumberDecimal("0.00") };
const fecha = { bsonType: "date" };
const requerido = (campos, propiedades) => ({
  bsonType: "object",
  required: campos,
  properties: propiedades,
  additionalProperties: false
});

const esquemas = {
  usuarios: requerido(
    ["_id", "nombre", "apellido", "nombre_usuario", "password_hash", "rol"],
    {
      _id: int,
      nombre: { bsonType: "string" },
      apellido: { bsonType: "string" },
      nombre_usuario: { bsonType: "string" },
      password_hash: { bsonType: "string" },
      rol: { enum: ["Administrador", "Recepcionista", "Personal de acceso", "Mantenimiento"] }
    }
  ),
  huespedes: requerido(
    ["_id", "nombre", "apellido", "rut", "telefono", "email", "direccion"],
    {
      _id: int,
      nombre: { bsonType: "string" },
      apellido: { bsonType: "string" },
      rut: { bsonType: "string" },
      telefono: { bsonType: ["string", "null"] },
      email: { bsonType: ["string", "null"] },
      direccion: { bsonType: ["string", "null"] }
    }
  ),
  habitaciones: requerido(
    ["_id", "numero", "tipo", "capacidad", "precio", "estado"],
    {
      _id: int,
      numero: int,
      tipo: { bsonType: "string" },
      capacidad: { bsonType: "int", minimum: 1 },
      precio: money,
      estado: { enum: ["Disponible", "Reservada", "Ocupada", "En limpieza", "Fuera de servicio"] }
    }
  ),
  servicios: requerido(
    ["_id", "nombre", "descripcion", "precio", "estado"],
    {
      _id: int,
      nombre: { bsonType: "string" },
      descripcion: { bsonType: ["string", "null"] },
      precio: money,
      estado: { enum: ["Activo", "Inactivo"] }
    }
  ),
  reservas: requerido(
    ["_id", "usuario_id", "huesped", "fecha_reserva", "fecha_entrada", "fecha_salida", "estado", "habitaciones", "estadia"],
    {
      _id: int,
      usuario_id: int,
      huesped: requerido(["_id", "rut", "nombre"], {
        _id: int,
        rut: { bsonType: "string" },
        nombre: { bsonType: "string" }
      }),
      fecha_reserva: fecha,
      fecha_entrada: fecha,
      fecha_salida: fecha,
      estado: { enum: ["Pendiente", "Confirmada", "Cancelada", "Finalizada"] },
      habitaciones: {
        bsonType: "array",
        minItems: 1,
        items: requerido(
          ["id_detalle", "habitacion_id", "numero", "tipo", "precio_noche"],
          {
            id_detalle: int,
            habitacion_id: int,
            numero: int,
            tipo: { bsonType: "string" },
            precio_noche: money
          }
        )
      },
      estadia: {
        bsonType: ["object", "null"],
        required: ["id_estadia", "fecha_checkin", "fecha_checkout", "estado", "cargos"],
        properties: {
          id_estadia: int,
          fecha_checkin: fecha,
          fecha_checkout: { bsonType: ["date", "null"] },
          estado: { enum: ["Activa", "Finalizada"] },
          cargos: {
            bsonType: "array",
            items: requerido(
              ["id_cargo", "servicio_id", "servicio", "cantidad", "precio_unitario", "subtotal", "fecha"],
              {
                id_cargo: int,
                servicio_id: int,
                servicio: { bsonType: "string" },
                cantidad: { bsonType: "int", minimum: 1 },
                precio_unitario: money,
                subtotal: money,
                fecha
              }
            )
          }
        },
        additionalProperties: false
      }
    }
  ),
  pagos: requerido(
    ["_id", "reserva_id", "fecha_pago", "monto", "metodo_pago", "estado"],
    {
      _id: int,
      reserva_id: int,
      fecha_pago: fecha,
      monto: { bsonType: "decimal", exclusiveMinimum: NumberDecimal("0.00") },
      metodo_pago: { bsonType: "string" },
      estado: { enum: ["Pagado", "Pendiente", "Anulado"] }
    }
  )
};

const indices = {
  usuarios: [{ keys: { nombre_usuario: 1 }, options: { unique: true } }],
  huespedes: [{ keys: { rut: 1 }, options: { unique: true } }],
  habitaciones: [{ keys: { numero: 1 }, options: { unique: true } }, { keys: { estado: 1 } }],
  servicios: [{ keys: { estado: 1 } }],
  reservas: [
    { keys: { "huesped._id": 1 } },
    { keys: { usuario_id: 1 } },
    { keys: { "habitaciones.habitacion_id": 1, fecha_entrada: 1, fecha_salida: 1 } },
    { keys: { estado: 1, fecha_entrada: 1 } }
  ],
  pagos: [
    { keys: { reserva_id: 1 } },
    { keys: { fecha_pago: 1, estado: 1 } }
  ]
};

const nombres = ["usuarios", "huespedes", "habitaciones", "servicios", "reservas", "pagos"];
const documentos = {};

for (const nombre of nombres) {
  const contenido = fs.readFileSync("./" + nombre + ".json", "utf8");
  const datos = EJSON.parse(contenido, { relaxed: false });
  if (!Array.isArray(datos) || datos.length === 0) {
    throw new Error("Archivo JSON sin arreglo de documentos: " + nombre);
  }
  documentos[nombre] = datos;
}

const existentes = hotelDb.getCollectionNames();
for (const nombre of nombres) {
  if (!existentes.includes(nombre)) {
    hotelDb.createCollection(nombre, {
      validator: { $jsonSchema: esquemas[nombre] },
      validationLevel: "strict",
      validationAction: "error"
    });
  } else {
    const resultado = hotelDb.runCommand({
      collMod: nombre,
      validator: { $jsonSchema: esquemas[nombre] },
      validationLevel: "strict",
      validationAction: "error"
    });
    if (!resultado.ok) throw new Error("Error al validar la colección " + nombre);
  }
  for (const indice of indices[nombre]) {
    hotelDb.getCollection(nombre).createIndex(indice.keys, indice.options || {});
  }
  for (const doc of documentos[nombre]) {
    hotelDb.getCollection(nombre).replaceOne({ _id: doc._id }, doc, { upsert: true });
  }
  print(nombre + ": " + hotelDb.getCollection(nombre).countDocuments({}) + " documentos");
}

print("Base lista: " + hotelDb.getName());
