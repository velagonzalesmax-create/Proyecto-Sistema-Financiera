#!/bin/bash
set -e

# Arrancar Apache directamente para asegurar que Render se ponga en Live
exec apache2-foreground