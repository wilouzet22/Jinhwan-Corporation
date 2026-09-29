# -*- coding: utf-8 -*-
fonts = [
    'Brush Script MT',
    'Dancing Script',
    'Satisfy',
    'Playball',
    'Kaushan Script',
    'Marck Script',
    'Parisienne',
    'Caveat',
    'Courgette',
    'Yellowtail'
]

css_links = [
    "https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap",
    "https://fonts.googleapis.com/css2?family=Satisfy&display=swap",
    "https://fonts.googleapis.com/css2?family=Playball&display=swap",
    "https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap",
    "https://fonts.googleapis.com/css2?family=Marck+Script&display=swap",
    "https://fonts.googleapis.com/css2?family=Parisienne&display=swap",
    "https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap",
    "https://fonts.googleapis.com/css2?family=Courgette&display=swap",
    "https://fonts.googleapis.com/css2?family=Yellowtail&display=swap"
]

html = "<!DOCTYPE html><html><head><meta charset='utf-8'>\n"
for l in css_links:
    html += f'<link href="{l}" rel="stylesheet">\n'

html += """<style>
body { font-size: 32px; padding: 40px; background: #fff; line-height: 1.5; color: #111; }
.card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
.meta { font-family: sans-serif; font-size: 14px; font-weight: bold; color: #555; margin-bottom: 8px; }
</style></head><body>
"""

sample = "Club de taekwondo Certifica que<br>Samuel Gomez Londono<br>Aprobó el examen reglamentario para Ascenso de grado<br>Cinturon Rojo P, Negra<br>Gup 1<br>16 de Noviembre del 2025"

for f in fonts:
    html += f'<div class="card"><div class="meta">{f}</div><div style="font-family: \'{f}\', cursive;">{sample}</div></div>\n'

html += "</body></html>"

with open("scratch/font_test.html", "w", encoding="utf-8") as out:
    out.write(html)

print("scratch/font_test.html generated")
