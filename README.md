# TuneFix MVC

Proyecto reestructurado con el patrón **Model – View – Controller (MVC)**.

---

## Estructura de carpetas

```
TuneFix_MVC/
│
├── config/
│   └── Database.php          ← Conexión PDO (Singleton)
│
├── models/
│   ├── MarcaModel.php        ← Consultas tabla `marca`
│   ├── ModeloModel.php       ← Consultas tabla `modelo`
│   ├── MotorizacionModel.php ← Consultas tabla `motorizacion`
│   ├── TutorialModel.php     ← Consultas tabla `tutorial`
│   └── PiezaModel.php        ← Consultas tabla `pieza`
│
├── controllers/
│   ├── HomeController.php         ← Lógica página de inicio
│   ├── PrincipianteController.php ← Lógica sección principiantes
│   └── AjaxController.php         ← Respuestas JSON para los selects
│
├── views/
│   ├── layouts/
│   │   ├── header.php   ← Cabecera + navbar (compartida)
│   │   └── footer.php   ← Pie de página (compartido)
│   ├── home/
│   │   └── index.php    ← Vista de selección de tipo de usuario
│   └── principiante/
│       └── index.php    ← Vista sección principiantes
│
├── public/
│   └── ajax/
│       ├── get_modelos.php        ← Endpoint AJAX → AjaxController::getModelos()
│       └── get_motorizaciones.php ← Endpoint AJAX → AjaxController::getMotorizaciones()
│
├── index.php         ← Punto de entrada (Home)
├── principiante.php  ← Punto de entrada (Principiante)
└── tunefix.sql       ← Base de datos
```

---

## Cómo funciona el MVC

| Capa | Responsabilidad |
|---|---|
| **Model** | Accede a la base de datos y devuelve datos. No sabe nada de HTML. |
| **View** | Solo muestra datos. No hace consultas SQL. Recibe variables del controlador. |
| **Controller** | Coordina: llama al modelo, prepara los datos y carga la vista. |

---

## Puesta en marcha

1. Importa `tunefix.sql` en tu servidor MySQL/MariaDB.
2. Edita `config/Database.php` con tus credenciales si es necesario.
3. Coloca la carpeta en tu servidor web (XAMPP, Laragon, etc.).
4. Accede a `http://localhost/TuneFix_MVC/` en el navegador.

---

## Flujo de una petición (ejemplo: Principiantes)

```
Navegador → principiante.php
              ↓
        PrincipianteController::index()
              ↓ llama a
        MarcaModel::getAll()
        TutorialModel::getRecientes(4)
        PiezaModel::getRecientes(6)
              ↓ pasa datos a
        views/layouts/header.php
        views/principiante/index.php   ← usa $marcas, $tutoriales, $piezas
        views/layouts/footer.php
```

## Flujo AJAX (cascada de selects)

```
JS fetch → public/ajax/get_modelos.php?marca_id=1
                ↓
          AjaxController::getModelos()
                ↓
          ModeloModel::getByMarca(1)
                ↓
          JSON → navegador → rellena el select de modelos
```
