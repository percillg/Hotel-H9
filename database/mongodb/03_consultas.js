const hotelDb = db.getSiblingDB("gestion_hotel_control2");

print("C1: Buscar huésped por RUT");
printjson(hotelDb.huespedes.findOne({ rut: "12.345.678-9" }));

print("C2: Ver habitaciones disponibles");
printjson(hotelDb.habitaciones.find({ estado: "Disponible" }, { numero: 1, tipo: 1, precio: 1 }).toArray());

print("C3: Ver la reserva completa con sus subdocumentos");
printjson(hotelDb.reservas.findOne({ _id: NumberInt(1) }));

print("C4: Historial de reservas de un huésped");
printjson(hotelDb.reservas.find({ "huesped._id": NumberInt(1) }, { huesped: 1, fecha_entrada: 1, fecha_salida: 1 }).toArray());

print("C5: Reservas con cargos de minibar");
printjson(hotelDb.reservas.find({ "estadia.cargos.servicio": "Minibar" }, { huesped: 1, "estadia.cargos": 1 }).toArray());

print("C6: Ver pagos asociados a una reserva");
printjson(hotelDb.pagos.find({ reserva_id: NumberInt(1) }).toArray());

print("C7: Reservas con fecha cruzada para la habitación 201 entre 2026-10-20 y 2026-10-23");
printjson(hotelDb.reservas.find({
  "habitaciones.habitacion_id": NumberInt(4),
  estado: { $in: ["Pendiente", "Confirmada"] },
  fecha_entrada: { $lt: ISODate("2026-10-23T00:00:00.000Z") },
  fecha_salida: { $gt: ISODate("2026-10-20T00:00:00.000Z") }
}, { habitaciones: 1, fecha_entrada: 1, fecha_salida: 1 }).toArray());

print("C8: Cuenta de la reserva con alojamiento, cargos, pagos y saldo");
printjson(hotelDb.reservas.aggregate([
  { $match: { _id: NumberInt(1) } },
  { $lookup: { from: "pagos", localField: "_id", foreignField: "reserva_id", as: "pagos_encontrados" } },
  { $addFields: { noches: { $dateDiff: { startDate: "$fecha_entrada", endDate: "$fecha_salida", unit: "day" } } } },
  { $addFields: {
    alojamiento: { $sum: { $map: { input: "$habitaciones", as: "h", in: { $multiply: ["$$h.precio_noche", "$noches"] } } } },
    cargos: { $sum: "$estadia.cargos.subtotal" },
    pagado: {
      $sum: {
        $map: {
          input: "$pagos_encontrados", as: "p",
          in: { $cond: [{ $eq: ["$$p.estado", "Pagado"] }, "$$p.monto", NumberDecimal("0.00")] }
        }
      }
    }
  } },
  { $project: {
    huesped: "$huesped.nombre", noches: 1, alojamiento: 1, cargos: 1, pagado: 1,
    saldo: { $subtract: [{ $add: ["$alojamiento", "$cargos"] }, "$pagado"] }
  } }
]).toArray());

print("C9: Total recaudado por método de pago");
printjson(hotelDb.pagos.aggregate([
  { $match: { estado: "Pagado" } },
  { $group: { _id: "$metodo_pago", total: { $sum: "$monto" }, cantidad: { $sum: 1 } } },
  { $sort: { total: -1 } }
]).toArray());

print("C10: Servicios consumidos agrupados");
printjson(hotelDb.reservas.aggregate([
  { $match: { estadia: { $ne: null } } },
  { $unwind: "$estadia.cargos" },
  { $group: {
    _id: "$estadia.cargos.servicio_id",
    servicio: { $first: "$estadia.cargos.servicio" },
    unidades: { $sum: "$estadia.cargos.cantidad" },
    importe: { $sum: "$estadia.cargos.subtotal" }
  } },
  { $sort: { importe: -1 } }
]).toArray());
