document.addEventListener('DOMContentLoaded', () => {
    // Inicializar el canvas con Fabric.js
    const canvas = new fabric.Canvas('salonCanvas');

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