document.addEventListener('DOMContentLoaded', () => {
    // Inicializar el canvas con Fabric.js
    const canvas = new fabric.Canvas('salonCanvas');

        // Agregar la puerta del restaurante al canvas
    const puerta = new fabric.Rect({
        left: 750, // Posición X de la puerta
        top: 20,  // Posición Y de la puerta
        fill: '#000000', // Color de la puerta (negro)
        width: 150, // Ancho de la puerta
        height: 20, // Altura de la puerta
        selectable: false, // No se puede seleccionar
        hasControls: false, // No tiene controles de transformación
        hoverCursor: 'default', // Cursor por defecto al pasar el mouse
    });
    
    // Agregar un texto opcional para identificar la puerta
    const textoPuerta = new fabric.Text('Puerta', {
        left: puerta.left + 10, // Posición relativa al rectángulo
        top: puerta.top - 20,   // Posición encima del rectángulo
        fontSize: 14,
        fill: '#000000', // Color del texto
        selectable: false, // No se puede seleccionar
        hoverCursor: 'default', // Cursor por defecto
    });
    
    // Agrupar la puerta y el texto
    const puertaGroup = new fabric.Group([puerta, textoPuerta], {
        selectable: false, // No se puede seleccionar el grupo
        hasControls: false, // No tiene controles de transformación
        hoverCursor: 'default', // Cursor por defecto al pasar el mouse
    });
    
    // Agregar la puerta al canvas
    canvas.add(puertaGroup);
    // Usar la variable global `mesas` definida en Blade
    if (typeof mesas !== 'undefined') {
        // Agregar mesas al canvas
        mesas.forEach(mesa => {
            let color;
            switch (mesa.estado) {
                case 'disponible':
                    color = '#1CCD8CFF'; // Verde pastel para disponible
                    break;
                case 'reservada':
                    color = '#FFCD37FF'; // Amarillo pastel para reservada
                    break;
                case 'ocupada':
                    color = '#ED362DFF'; // Rojo pastel para ocupada
                    break;
                default:
                    color = '#D3D3D3'; // Gris claro para estados desconocidos
            }

            let width = 120;
            let height = 120;

            if (mesa.capacidad >= 4) {
                width += mesa.capacidad * 10; // Aumentar el ancho según la capacidad
            }

            const borderRadius = 10;
            const posX = mesa.x !== 50 ? mesa.x : Math.random() * 1000;
            const posY = mesa.y !== 50 ? mesa.y : Math.random() * 1000;
            const mesaRect = new fabric.Rect({
                left: posX, // Posición X (por defecto 50 si no está definida)
                top: posY,  // Posición Y (por defecto 50 si no está definida)
                fill: color,      // Color de la mesa
                width: width,          // Ancho de la mesa
                height: height,         // Altura de la mesa
                rx: borderRadius, // Radio de las esquinas
                ry: borderRadius, // Radio de las esquinas
                id: mesa.id,        // ID de la mesa
                nombre: mesa.nombre // Nombre de la mesa
            });

            // Agregar texto con el nombre de la mesa
            const mesaText = new fabric.Text(mesa.nombre + "\nCapacidad: " + mesa.capacidad, {
                left: mesaRect.left + 10,
                top: mesaRect.top + 40,
                fontSize: 18,
                fill: 'white',
                selectable: false // El texto no será seleccionable
            });

            // Agrupar el rectángulo y el texto
            const mesaGroup = new fabric.Group([mesaRect, mesaText], {
                left: mesaRect.left,
                top: mesaRect.top,
                id: mesa.id
            });

            if (mesa.capacidad <= 4) {
                mesaGroup.set({ lockRotation: true }); // Bloquear rotación para mesas pequeñas
            }else{
                mesaGroup.set({ lockRotation: false }); // Permitir rotación para mesas grandes
            }

            mesaGroup.on('mousedblclick', () => {
                if (mesa.estado === 'disponible' || mesa.estado === 'reservada') {
                    // Redirigir al formulario de creación de pedido
                    window.location.href = `/pedidos/create/${mesa.id}`;
                } else {
                    // Obtener el ID del pedido asociado a la mesa
                    fetch(`/mesas/${mesa.id}/pedido`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('No se encontró un pedido asociado a esta mesa.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.pedido_id) {
                                // Redirigir a los detalles del pedido
                                window.location.href = `/pedidos/${data.pedido_id}`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('No se encontró un pedido asociado a esta mesa.');
                        });
                }
            });
            
            // Hacer que la mesa sea movible
            mesaGroup.set({ hasControls: true });

            // Agregar la mesa al canvas
            canvas.add(mesaGroup);
        });

        // Guardar la posición de las mesas al soltar
        canvas.on('object:modified', (e) => {
            const obj = e.target;
            if (obj && obj.id) {
                // Enviar la nueva posición al servidor
                fetch(`/mesas/${obj.id}/update-position`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        x: obj.left,
                        y: obj.top
                    })
                }).then(response => {
                    if (response.ok) {
                        console.log(`Mesa ${obj.id} actualizada`);
                    } else {
                        console.error('Error al actualizar la posición');
                    }
                }).catch(error => console.error('Error:', error));
            }
        });
    } else {
        console.error('No se encontraron mesas para cargar en el canvas.');
    }
});

/* document.addEventListener('DOMContentLoaded', () => {
    const canvas = new fabric.Canvas('salonCanvas');

    if (typeof mesas !== 'undefined') {
        mesas.forEach(mesa => {
            let color;
            switch (mesa.estado) {
                case 'disponible':
                    color = '#1CCD8CFF';
                    break;
                case 'reservada':
                    color = '#FFCD37FF';
                    break;
                case 'ocupada':
                    color = '#ED362DFF';
                    break;
                default:
                    color = '#D3D3D3';
            }

            let width = 120;
            let height = 120;

            if (mesa.capacidad > 4) {
                width += mesa.capacidad * 10;
            }

            const borderRadius = 10;
            const posX = mesa.x !== 50 ? mesa.x : Math.random() * 1000;
            const posY = mesa.y !== 50 ? mesa.y : Math.random() * 1000;

            const mesaRect = new fabric.Rect({
                left: posX,
                top: posY,
                fill: color,
                width: width,
                height: height,
                rx: borderRadius,
                ry: borderRadius,
                id: mesa.id,
                nombre: mesa.nombre,
                angle: mesa.rotada ? 90 : 0 // Aplicar rotación inicial según la columna "rotada"
            });

            const mesaText = new fabric.Text(mesa.nombre + "\nCapacidad: " + mesa.capacidad, {
                left: mesaRect.left + 10,
                top: mesaRect.top + 40,
                fontSize: 18,
                fill: 'white',
                selectable: false
            });

            const mesaGroup = new fabric.Group([mesaRect, mesaText], {
                left: mesaRect.left,
                top: mesaRect.top,
                id: mesa.id
            });

            if (mesa.capacidad > 4) {
                mesaGroup.set({ lockRotation: false }); // Permitir rotación para mesas grandes
            } else {
                mesaGroup.set({ lockRotation: true }); // Bloquear rotación para mesas pequeñas
            }

            mesaGroup.on('mousedblclick', () => {
                if (mesa.estado === 'disponible' || mesa.estado === 'reservada') {
                    // Redirigir al formulario de creación de pedido
                    window.location.href = `/pedidos/create/${mesa.id}`;
                } else {
                    // Obtener el ID del pedido asociado a la mesa
                    fetch(`/mesas/${mesa.id}/pedido`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('No se encontró un pedido asociado a esta mesa.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.pedido_id) {
                                // Redirigir a los detalles del pedido
                                window.location.href = `/pedidos/${data.pedido_id}`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('No se encontró un pedido asociado a esta mesa.');
                        });
                }
            });
            
            // Hacer que la mesa sea movible
            mesaGroup.set({ hasControls: true });

            // Detectar rotación y actualizar la base de datos
            mesaGroup.on('rotating', () => {
                if (mesaGroup.angle >= 45 && mesaGroup.angle <= 135) {
                    mesaGroup.angle = 90; // Fijar en vertical
                } else {
                    mesaGroup.angle = 0; // Fijar en horizontal
                }
            });

            mesaGroup.on('modified', () => {
                const isRotated = mesaGroup.angle === 90;

                // Enviar la nueva posición y rotación al servidor
                fetch(`/mesas/${mesa.id}/update-position`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        x: mesaGroup.left,
                        y: mesaGroup.top,
                        rotada: isRotated
                    })
                }).then(response => {
                    if (response.ok) {
                        console.log(`Mesa ${mesa.id} actualizada`);
                    } else {
                        console.error('Error al actualizar la posición y rotación');
                    }
                }).catch(error => console.error('Error:', error));
            });

            canvas.add(mesaGroup);
        });

        canvas.on('object:modified', (e) => {
            const obj = e.target;
            if (obj && obj.id) {
                console.log(`Mesa ${obj.id} modificada`);
            }
        });
    } else {
        console.error('No se encontraron mesas para cargar en el canvas.');
    }
}); */