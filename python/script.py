import psycopg2

try:
    conn = psycopg2.connect("host=db dbname=portafolio_db user=usuario_p1 password=password123")
    cur = conn.cursor()
    
    cur.execute("SELECT COUNT(*) FROM tickets")
    total = cur.fetchone()[0]
    
    with open("/app/reports/reporte.txt", "w") as f:
        f.write("CANTIDAD TOTAL DE TICKETS: " + str(total))
    
    cur.close()
    conn.close()
except Exception as e:
    print("Error en el script")