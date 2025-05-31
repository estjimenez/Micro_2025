const URL = 'http://localhost:8000/api/app';
let editando = false;
let idOriginal = '';
let todasLasHistorias = [];
let historiasFiltradas = [];

function editarHistoria(id) {
    editando = true;
    idOriginal = id;

    fetch(`${URL}/historias/${id}`)
        .then(res => res.json())
        .then(historia => {
            document.getElementById('titulo').value = historia.titulo;
            document.getElementById('descripcion').value = historia.descripcion;
            document.getElementById('responsable').value = historia.responsable;
            document.getElementById('estado').value = historia.estado;
            document.getElementById('puntos').value = historia.puntos;
            document.getElementById('fecha_creacion').value = historia.fecha_creacion;
            document.getElementById('fecha_finalizacion').value = historia.fecha_finalizacion || '';
            document.getElementById('sprint_id').value = historia.sprint_id;
            document.getElementById('tituloFormulario').textContent = 'Editar Historia';
        })
        .catch(err => alert('Error al cargar historia'));
}

function cancelarEdicion() {
    editando = false;
    idOriginal = '';
    document.getElementById('formularioHistoria').reset();
    document.getElementById('tituloFormulario').textContent = 'Agregar Historia';
}

function cargarHistorias() {
    fetch(`${URL}/historias`)
        .then(res => res.json())
        .then(data => {
            todasLasHistorias = data;
            historiasFiltradas = data;
            mostrarHistorias();
        });
}

function mostrarHistorias() {
    const tabla = document.getElementById('tablaHistorias');
    tabla.innerHTML = '';

    historiasFiltradas.forEach(h => {
        tabla.innerHTML += `
            <tr>
                <td>${h.id}</td>
                <td>${h.titulo}</td>
                <td>${h.responsable}</td>
                <td>${h.estado}</td>
                <td>${h.puntos}</td>
                <td>${h.sprint ? h.sprint.nombre : 'Sin sprint'}</td>
                <td>
                    <button onclick="editarHistoria(${h.id})">Editar</button>
                    <button onclick="eliminarHistoria(${h.id}, '${h.titulo.replace(/'/g, "\\'")}')">Eliminar</button>
                </td>
            </tr>
        `;
    });
}

function aplicarFiltros() {
    const responsable = document.getElementById('filtroResponsable').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value;

    historiasFiltradas = todasLasHistorias.filter(h =>
        (!responsable || h.responsable.toLowerCase().includes(responsable)) &&
        (!estado || h.estado === estado)
    );

    mostrarHistorias();
}

function limpiarFiltros() {
    document.getElementById('filtroResponsable').value = '';
    document.getElementById('filtroEstado').value = '';
    historiasFiltradas = todasLasHistorias;
    mostrarHistorias();
}

function eliminarHistoria(id, titulo) {
    if (confirm(`¿Eliminar historia "${titulo}"?`)) {
        fetch(`${URL}/historias/${id}`, { method: 'DELETE' })
            .then(res => res.json())
            .then(resp => {
                alert(resp.mensaje);
                cargarHistorias();
            });
    }
}

function guardarHistoria(e) {
    e.preventDefault();

    const datos = {
        titulo: document.getElementById('titulo').value.trim(),
        descripcion: document.getElementById('descripcion').value.trim(),
        responsable: document.getElementById('responsable').value.trim(),
        estado: document.getElementById('estado').value,
        puntos: parseInt(document.getElementById('puntos').value, 10),
        fecha_creacion: document.getElementById('fecha_creacion').value,
        fecha_finalizacion: document.getElementById('fecha_finalizacion').value || null,
        sprint_id: parseInt(document.getElementById('sprint_id').value, 10),
    };

    const url = editando ? `${URL}/historias/${idOriginal}` : `${URL}/historias`;
    const method = editando ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(async res => {
        const text = await res.text();
        try {
            const data = JSON.parse(text);
            if (!res.ok) throw new Error(data.message || 'Error desconocido');
            return data;
        } catch {
            throw new Error('Respuesta no válida:\n' + text);
        }
    })
    .then(() => {
        alert(editando ? 'Historia actualizada' : 'Historia creada');
        cancelarEdicion();
        cargarHistorias();
    })
    .catch(err => {
        alert('Error al guardar historia:\n' + err.message);
        console.error(err);
    });
}

function guardarSprint(e) {
    e.preventDefault();

    const nombre = document.getElementById('nombreSprint').value.trim();
    const fecha_inicio = document.getElementById('fechaInicioSprint').value;
    const fecha_fin = document.getElementById('fechaFinSprint').value;

    if (!nombre || !fecha_inicio || !fecha_fin) {
        alert('Completa todos los campos del sprint');
        return;
    }

    const datos = { nombre, fecha_inicio, fecha_fin };

    fetch(`${URL}/sprints`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(async res => {
        const text = await res.text();
        try {
            const data = JSON.parse(text);
            if (!res.ok) throw new Error(data.message || 'Error al crear sprint');
            alert('Sprint creado correctamente');
            document.getElementById('formularioSprint').reset();
        } catch (err) {
            throw new Error('Respuesta inválida:\n' + text);
        }
    })
    .catch(err => {
        alert('Error al guardar el sprint:\n' + err.message);
        console.error(err);
    });
}

window.onload = function() {
    cargarHistorias();
    document.getElementById('formularioHistoria').onsubmit = guardarHistoria;
    document.getElementById('formularioSprint').onsubmit = guardarSprint; // <-- ESTA LÍNEA ES LA NUEVA
    document.getElementById('btnAplicarFiltros').onclick = aplicarFiltros;
    document.getElementById('btnLimpiarFiltros').onclick = limpiarFiltros;
};