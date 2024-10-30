// Importa módulos necesarios
const fs = require("fs").promises;
const path = require("path");

const filePath = path.join(__dirname, "likes.json");

// Inicializa el archivo de likes si no existe
async function initializeLikesFile() {
  try {
    await fs.access(filePath);
  } catch {
    await fs.writeFile(filePath, JSON.stringify({ likes: 0 }));
  }
}

// Handler de la función
exports.handler = async (event) => {
  await initializeLikesFile();
  
  if (event.httpMethod === "POST") {
    // Incrementa el contador de "likes"
    try {
      const data = await fs.readFile(filePath, "utf-8");
      const likesData = JSON.parse(data);
      likesData.likes += 1;
      
      await fs.writeFile(filePath, JSON.stringify(likesData));
      
      return {
        statusCode: 200,
        body: JSON.stringify(likesData),
      };
    } catch (error) {
      return {
        statusCode: 500,
        body: JSON.stringify({ error: "Error incrementando likes" }),
      };
    }
  } else if (event.httpMethod === "GET") {
    // Retorna el número actual de "likes"
    try {
      const data = await fs.readFile(filePath, "utf-8");
      const likesData = JSON.parse(data);
      
      return {
        statusCode: 200,
        body: JSON.stringify(likesData),
      };
    } catch (error) {
      return {
        statusCode: 500,
        body: JSON.stringify({ error: "Error obteniendo likes" }),
      };
    }
  }

  return {
    statusCode: 405,
    body: "Método no permitido",
  };
};