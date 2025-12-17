# PLUGIN DESACTUALIZADO! YA NO JUEGO MINECRAFT BEDROCK EDITION
# ESTOY TRATANDO DE HACER UN JUEGO EN ROBLOX STUDIO
# Size Plugin para PocketMine-MP 5

Plugin desarrollado por **ySimmer** que permite cambiar el tamaño de los jugadores en el servidor con notificaciones en ActionBar y efectos de sonido personalizables.

## 📋 Comandos Disponibles

| Comando | Descripción | Permiso |
|---------|-------------|---------|
| `/size set <0.1-10>` | Cambia tu propio tamaño | `size.use` |
| `/size set <0.1-10> <jugador>` | Cambia el tamaño de otro jugador | `size.others` |
| `/size reset` | Resetea tu tamaño al predeterminado (1.0) | `size.use` |
| `/size reset <jugador>` | Resetea el tamaño de otro jugador | `size.others` |
| `/size credits` | Muestra los créditos del plugin | `size.use` |
| `/size help` | Lista todos los comandos disponibles | `size.use` |

## 🔧 Instalación

1. Descarga el plugin
2. Coloca la carpeta del plugin en `plugins/`
3. Reinicia el servidor
4. El plugin se cargará automáticamente
5. Personaliza el archivo `config.yml` según tus preferencias

## ⚙️ Configuración

El plugin incluye un archivo `config.yml` totalmente personalizable:

### Configuración de Sonidos
```yaml
sounds:
  enabled: true                    # Activa/desactiva sonidos
  sound_name: "note.bell"          # Tipo de sonido
  volume: 1.0                      # Volumen (0.0 - 1.0)
  pitch: 1.0                       # Tono (0.0 - 2.0)
```

**Sonidos disponibles:**
- `note.bell` - Campana
- `random.orb` - Orbe de experiencia
- `random.levelup` - Subir de nivel
- `mob.villager.yes` - Aldeano afirmativo

### Personalización de Mensajes

Todos los mensajes del plugin son personalizables. Puedes usar:
- `{player}` - Nombre del jugador
- `{size}` - Tamaño establecido
- Códigos de color de Minecraft (§a, §6, §c, etc.)

## 🎯 Permisos

- `size.use` - Permite usar comandos básicos (predeterminado: true)
- `size.others` - Permite cambiar el tamaño de otros jugadores (predeterminado: op)
- `size.admin` - Acceso completo a los comandos (predeterminado: op)

## 📝 Ejemplos de Uso

```
/size set 2.0          # Duplica tu tamaño
/size set 0.5          # Reduce tu tamaño a la mitad
/size set 1.5 Steve    # Define tamaño 1.5 para el jugador Steve
/size reset            # Vuelve al tamaño normal
/size reset Alex       # Resetea el tamaño del jugador Alex
/size credits          # Muestra créditos
/size help             # Lista comandos
```

## 🎮 Características

- ✨ Notificaciones en ActionBar con colores personalizables
- 🔔 Efectos de sonido configurables
- 🎯 Sistema de permisos integrado
- 💬 Mensajes completamente personalizables
- ⚙️ Archivo de configuración flexible
- 🌍 Mensajes en español (personalizable a cualquier idioma)

## ⚙️ Requisitos

- PocketMine-MP 5.0.0 o superior
- PHP 8.0 o superior

## 👨‍💻 Desarrollador

**ySimmer** - Creador del plugin

---

*Plugin Size v1.0.0 - Todos los derechos reservados*
