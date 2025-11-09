document.addEventListener('DOMContentLoaded', () => {
    // Inicializar el canvas con Fabric.js
    const canvas = new fabric.Canvas('salonCanvas');

    // Usar la variable global `mesas` definida en Blade
    if (typeof mesas !== 'undefined') {
        // Agregar mesas al canvas
        mesas.forEach(mesa => {
            const mesaRect = new fabric.Rect({
                left: mesa.x || 50, // Posición X (por defecto 50 si no está definida)
                top: mesa.y || 50,  // Posición Y (por defecto 50 si no está definida)
                fill: 'green',      // Color de la mesa
                width: 80,          // Ancho de la mesa
                height: 80,         // Altura de la mesa
                id: mesa.id,        // ID de la mesa
                nombre: mesa.nombre // Nombre de la mesa
            });

            // Agregar texto con el nombre de la mesa
            const mesaText = new fabric.Text(mesa.nombre, {
                left: mesaRect.left + 10,
                top: mesaRect.top + 30,
                fontSize: 14,
                fill: 'white',
                selectable: false // El texto no será seleccionable
            });

            // Agrupar el rectángulo y el texto
            const mesaGroup = new fabric.Group([mesaRect, mesaText], {
                left: mesaRect.left,
                top: mesaRect.top,
                id: mesa.id
            });

            // Hacer que la mesa sea movible
            mesaGroup.set({ hasControls: true, lockRotation: true });

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