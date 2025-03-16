# Tutorials Català 🚀  
Un projecte de reparació amb guies detallades fetes per la comunitat.  

## 📌 Funcionalitats
- 📖 Visualització de guies de reparació  
- ⏳ Informació sobre temps estimat  
- 🛠️ Llista d'eines necessàries  

## 🎥 Demo
[![Captura de pantalla](./Captura%20de%20pantalla%202025-03-16%20013155.png)](https://drive.google.com/file/d/1G1SDhKVHvMBtGOlLP6KypDBB9zbJemk-/view?usp=sharing)

🔗 **Fes clic a la imatge per veure el vídeo**

## 🛠️ Instal·lació

Segueix aquests passos per configurar i executar el projecte correctament:

### 1️⃣ Clonar el repositori
Primer, descarrega el codi font del projecte i accedeix a la carpeta del projecte:
```bash
git clone https://github.com/farahtaab/tutorials-catala.git
cd tutorials-catala
```

---

### 2️⃣ Instal·lar dependències
Aquest projecte utilitza Laravel, que requereix Composer per gestionar les seves dependències. Si no tens Composer instal·lat, pots descarregar-lo des d’[aquí](https://getcomposer.org/).

Executa la següent comanda per instal·lar totes les dependències:
```bash
composer install
```

---

### 3️⃣ Configurar l’arxiu `.env`
- Crea una còpia de l'arxiu de configuració per poder personalitzar-lo:
  ```bash
  cp .env.example .env
  ```
- Edita el fitxer `.env` i assegura't de configurar correctament la connexió a la base de dades:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=nom_de_la_base_de_dades
  DB_USERNAME=usuari
  DB_PASSWORD=contrasenya
  ```

---

### 4️⃣ Generar la clau de l'aplicació
Laravel requereix una clau única per a encriptació. Genera-la amb la següent comanda:
```bash
php artisan key:generate
```

---

### 5️⃣ Executar les migracions de la base de dades
Per crear les taules a la base de dades, executa:
```bash
php artisan migrate
```

Si vols carregar dades inicials:
```bash
php artisan db:seed
```

---

### 6️⃣ Iniciar el servidor de desenvolupament
Finalment, per executar l'aplicació en un servidor local:
```bash
php artisan serve
```
Això iniciarà el servidor a `http://127.0.0.1:8000/`.

---

### 7️⃣ Opcional: Compilar assets (JS, CSS)
Si el projecte utilitza Laravel Mix o Vite per gestionar els assets, executa:
```bash
npm install
npm run dev
```

Si necessites compilar els assets per a producció:
```bash
npm run build
```

