# Prompt para Claude Code — Sistema de Proveedores TuneFix

## Contexto del proyecto
TuneFix es una plataforma web PHP (servidor XAMPP) sobre recambios de coche, tutoriales en vídeo, manuales y proveedores. Tiene 3 tipos de usuarios: **Principiante**, **Entusiasta** y **Profesional**. Ahora vamos a añadir un cuarto rol: **Proveedor**, con un flujo de registro, validación por admin, y panel propio.

---

## LO QUE TIENES QUE IMPLEMENTAR

### 1. PANTALLA PRINCIPAL — Banner de registro para proveedores

En la página principal (antes de hacer login), añade una sección o banner visible con el siguiente contenido:

- Texto: *"¿Eres una empresa o distribuidor de recambios? Únete a TuneFix como Proveedor Verificado"*
- Botón: **"Solicitar acceso como Proveedor"**
- Al pulsar el botón, redirige al formulario de solicitud (ver punto 2)
- El banner debe estar visualmente diferenciado del resto del contenido (fondo distinto, icono de tienda o camión)

---

### 2. FORMULARIO DE SOLICITUD DE PROVEEDOR

Crea una página/ruta `/solicitud-proveedor` con un formulario que recoja los siguientes campos:

**Datos de empresa:**
- Nombre de la empresa (texto, obligatorio)
- CIF / NIF de la empresa (texto, obligatorio, formato validado)
- Dirección fiscal completa (texto, obligatorio)
- Provincia / Comunidad Autónoma (select, obligatorio)
- Teléfono de contacto empresarial (teléfono, obligatorio)
- Sitio web de la empresa (URL, opcional)

**Datos del responsable:**
- Nombre completo del responsable (texto, obligatorio)
- Email de contacto (email, obligatorio)
- Contraseña (para crear su cuenta, mínimo 8 caracteres)
- Confirmar contraseña

**Documentación:**
- Subida de archivo: Documento CIF / escritura de empresa (PDF o imagen, obligatorio)
- Subida de archivo: Alta en IAE o certificado de actividad (PDF o imagen, obligatorio)

**Descripción:**
- Textarea: "Describe brevemente qué tipo de recambios o piezas ofreces" (obligatorio, mínimo 50 caracteres)

Al enviar el formulario:
- Guardar todos los datos en la base de datos con estado `pendiente`
- Guardar los archivos subidos en una carpeta protegida (no accesible públicamente)
- Mostrar mensaje de confirmación: *"Tu solicitud ha sido enviada. Revisaremos tu documentación y recibirás una respuesta por email en un plazo de 48 horas."*
- Enviar email de notificación al administrador avisando de nueva solicitud pendiente

---

### 3. BACKOFFICE — Gestión de solicitudes de proveedores

En el panel de administración existente, añade una sección **"Proveedores"** con las siguientes subsecciones:

#### 3.1 Lista de solicitudes pendientes
- Tabla con columnas: Empresa, CIF, Responsable, Email, Fecha de solicitud, Estado, Acciones
- Filtros por estado: Todos / Pendientes / Aceptados / Rechazados
- Cada fila tiene botón **"Ver detalle"**

#### 3.2 Detalle de solicitud
- Muestra todos los datos del formulario
- Muestra los documentos subidos (con opción de descargarlos o previsualizarlos)
- Dos botones de acción:
  - **✅ Aceptar proveedor** → cambia estado a `aceptado`, activa la cuenta, envía email al proveedor con sus credenciales y enlace de acceso
  - **❌ Rechazar solicitud** → abre modal pidiendo motivo del rechazo, cambia estado a `rechazado`, envía email al proveedor explicando el motivo

---

### 4. CONTROL DE ACCESO DEL PROVEEDOR

- Un proveedor con estado `pendiente` o `rechazado` **NO puede hacer login**. Si intenta acceder, mostrar mensaje: *"Tu solicitud aún no ha sido aprobada. Te avisaremos por email cuando sea revisada."*
- Solo los proveedores con estado `aceptado` pueden iniciar sesión
- Al hacer login, el proveedor va a su propio panel (ver punto 5), NO al panel de usuario normal

---

### 5. PANEL DEL PROVEEDOR (interfaz propia)

Cuando un proveedor aceptado hace login, entra a un dashboard propio en `/proveedor/dashboard` con:

**Barra lateral con menú:**
- 🏠 Dashboard
- 📦 Mis Piezas
- ➕ Publicar nueva pieza
- 📊 Estadísticas (visitas y contactos de sus piezas)
- 👤 Mi perfil de empresa
- 🚪 Cerrar sesión

**Dashboard principal:**
- Bienvenida con el nombre de la empresa y badge "Proveedor Verificado ✓"
- Tarjetas resumen: Total de piezas publicadas, Piezas activas, Contactos recibidos esta semana
- Últimas piezas publicadas (tabla con estado: activa/pausada/sin stock)

---

### 6. PUBLICAR UNA PIEZA — Vinculación obligatoria con vehículo

Cuando el proveedor va a subir una pieza, el formulario debe tener los siguientes campos y en este orden:

**Primero: Selección del vehículo compatible (OBLIGATORIO)**
El proveedor debe indicar con qué vehículos es compatible la pieza. Para cada vehículo añadido:
- Marca del coche (select con marcas populares: Seat, Volkswagen, Ford, Renault, Toyota, BMW, Mercedes, Audi, Opel, Peugeot, Citroën, Honda, Hyundai, Kia, Nissan, Fiat, Otros)
- Modelo (texto o select dinámico según marca)
- Año desde (número, ej: 2005)
- Año hasta (número, ej: 2015, o "Actualidad")
- Motor / versión (texto opcional, ej: "1.6 TDI 105cv")

El proveedor puede añadir **múltiples vehículos compatibles** con un botón "+ Añadir otro vehículo compatible". Debe haber al menos 1 vehículo.

**Luego: Datos de la pieza**
- Nombre de la pieza (texto, obligatorio)
- Referencia OEM / número de pieza (texto, opcional pero recomendado)
- Categoría (select: Motor, Frenos, Suspensión, Transmisión, Carrocería, Electricidad, Climatización, Escape, Filtros, Otros)
- Estado: Nueva / Usada en buen estado / Usada con desgaste
- Precio (número decimal, obligatorio)
- Stock disponible (número entero, obligatorio)
- Descripción detallada (textarea, obligatorio)
- Fotos de la pieza (múltiples imágenes, mínimo 1, máximo 6)
- Garantía ofrecida (select: Sin garantía / 1 mes / 3 meses / 6 meses / 1 año)

Al publicar, la pieza aparece en el catálogo de TuneFix vinculada a los vehículos indicados, y los usuarios pueden buscar piezas filtrando por su coche.

---

### 7. BASE DE DATOS — Tablas necesarias

Crea o modifica las siguientes tablas:

```sql
-- Tabla de proveedores
CREATE TABLE proveedores (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre_empresa VARCHAR(255) NOT NULL,
  cif VARCHAR(20) NOT NULL UNIQUE,
  direccion TEXT NOT NULL,
  provincia VARCHAR(100),
  telefono VARCHAR(20),
  sitio_web VARCHAR(255),
  nombre_responsable VARCHAR(255),
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  descripcion TEXT,
  doc_cif VARCHAR(255),        -- ruta del archivo subido
  doc_iae VARCHAR(255),        -- ruta del archivo subido
  estado ENUM('pendiente', 'aceptado', 'rechazado') DEFAULT 'pendiente',
  motivo_rechazo TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de piezas
CREATE TABLE piezas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  proveedor_id INT NOT NULL,
  nombre VARCHAR(255) NOT NULL,
  referencia_oem VARCHAR(100),
  categoria VARCHAR(100),
  estado_pieza ENUM('nueva', 'usada_buena', 'usada_desgaste'),
  precio DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  descripcion TEXT,
  garantia VARCHAR(50),
  activa BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
);

-- Fotos de las piezas
CREATE TABLE piezas_fotos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  pieza_id INT NOT NULL,
  ruta VARCHAR(255) NOT NULL,
  orden INT DEFAULT 0,
  FOREIGN KEY (pieza_id) REFERENCES piezas(id)
);

-- Vehículos compatibles con cada pieza
CREATE TABLE piezas_vehiculos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  pieza_id INT NOT NULL,
  marca VARCHAR(100) NOT NULL,
  modelo VARCHAR(100) NOT NULL,
  anio_desde INT,
  anio_hasta INT,
  motor VARCHAR(100),
  FOREIGN KEY (pieza_id) REFERENCES piezas(id)
);
```

---

### 8. NOTAS TÉCNICAS

- Usa el sistema de sesiones PHP ya existente en el proyecto para diferenciar el rol `proveedor` de los demás roles
- Las contraseñas deben guardarse con `password_hash()` y verificarse con `password_verify()`
- Los archivos de documentación subidos deben ir a una carpeta fuera del webroot o con un `.htaccess` que bloquee el acceso directo
- El envío de emails puede hacerse con PHPMailer o la función `mail()` nativa si el proyecto ya tiene algo configurado
- Adapta rutas, nombres de archivos y estructura de carpetas al patrón que ya sigue el proyecto existente
- Si el proyecto usa algún framework PHP (Laravel, CodeIgniter, etc.), adapta todo a sus convenciones (rutas, modelos, vistas, middlewares)

---

### RESUMEN DE LO QUE SE PIDE

1. Banner en home con botón para solicitar acceso como proveedor
2. Formulario de solicitud con datos de empresa, responsable y documentos
3. Sección en el backoffice para ver solicitudes pendientes y aceptar/rechazar
4. Control de acceso: solo proveedores aceptados pueden hacer login
5. Panel propio del proveedor con dashboard, listado de piezas y estadísticas
6. Formulario de publicación de piezas con vinculación obligatoria a vehículos compatibles
7. Tablas de base de datos para soportar todo lo anterior
