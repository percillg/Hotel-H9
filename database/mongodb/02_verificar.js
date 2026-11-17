const hotelDb = db.getSiblingDB("gestion_hotel_control2");
const esperados = {
  huespedes: 3,
  habitaciones: 5,
  servicios: 3,
  reservas: 3,
  pagos: 1
};

for (const [nombre, cantidad] of Object.entries(esperados)) {
  const real = hotelDb.getCollection(nombre).countDocuments({});
  print(nombre + ": " + real + " documentos");
  if (real !== cantidad) throw new Error("Cantidad inesperada en " + nombre);
}

for (const reserva of hotelDb.reservas.find()) {
  if (reserva.fecha_salida <= reserva.fecha_entrada) throw new Error("Fechas incorrectas en reserva " + reserva._id);
  // usuario_id apunta a la tabla usuarios de MySQL (no se verifica desde MongoDB)
  if (!hotelDb.huespedes.findOne({ _id: reserva.huesped._id })) throw new Error("Huésped inexistente");
  for (const item of reserva.habitaciones) {
    if (!hotelDb.habitaciones.findOne({ _id: item.habitacion_id })) throw new Error("Habitación inexistente");
  }
  if (reserva.estadia !== null) {
    for (const cargo of reserva.estadia.cargos) {
      if (!hotelDb.servicios.findOne({ _id: cargo.servicio_id })) throw new Error("Servicio inexistente");
    }
  }
}

for (const pago of hotelDb.pagos.find()) {
  if (!hotelDb.reservas.findOne({ _id: pago.reserva_id })) throw new Error("Pago sin reserva");
}

if (hotelDb.getCollectionNames().includes("usuarios")) throw new Error("La colección usuarios todavía existe en MongoDB");

print("Verificación completada: cinco colecciones y referencias válidas");
