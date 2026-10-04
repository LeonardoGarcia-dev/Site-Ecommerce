# 🛒 Site E-commerce

Aplicação web de loja virtual desenvolvida em **PHP**, com vitrine de produtos, carrinho de compras e um **painel administrativo** para gerenciamento da loja.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## 📑 Sumário

- [Sobre o projeto](#-sobre-o-projeto)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias](#-tecnologias)
- [Estrutura de pastas](#-estrutura-de-pastas)
- [Como executar](#-como-executar)
- [Fluxo de trabalho da equipe](#-fluxo-de-trabalho-da-equipe)
- [Melhorias futuras](#-melhorias-futuras)
- [Autores](#-autores)

---

## 📖 Sobre o projeto

O **Site E-commerce** é um projeto de loja online que simula o fluxo completo de compra: o cliente navega pelos produtos, adiciona itens ao carrinho e finaliza o pedido, enquanto o administrador gerencia o conteúdo da loja por meio de um painel exclusivo.

O código foi organizado de forma modular, separando páginas, componentes reutilizáveis, operações de CRUD e a área administrativa, o que facilita a manutenção e o trabalho em equipe.

---

## ✨ Funcionalidades

### 👤 Área do cliente
- Página inicial da loja
- Listagem e visualização de produtos
- Carrinho de compras
- Envio de e-mails automáticos via **PHPMailer**
- Layout com componentes reutilizáveis (cabeçalho, rodapé etc.)

### 🔐 Painel administrativo
- Acesso restrito à administração da loja
- Operações **CRUD** (criar, listar, editar e excluir) para gerenciamento do conteúdo da loja

---

## 🛠 Tecnologias

| Tecnologia | Uso |
| --- | --- |
| **PHP** | Linguagem principal e lógica do servidor |
| **HTML5 / CSS3** | Estrutura e estilização das páginas |
| **JavaScript** | Interações no lado do cliente |
| **PHPMailer** | Envio de e-mails |
| **Git / GitHub** | Versionamento e colaboração |

---

## 📂 Estrutura de pastas

```
Site-Ecommerce/
├── PHPMailer/      # Biblioteca para envio de e-mails
├── admin/          # Painel administrativo
├── assets/         # Imagens, estilos e scripts
├── components/     # Componentes reutilizáveis (header, footer etc.)
├── config/         # Arquivos de configuração da aplicação
├── crud/           # Operações de criação, leitura, atualização e exclusão
├── pages/          # Páginas do site (produtos, carrinho etc.)
└── index.php       # Ponto de entrada da aplicação
```

---

## 🚀 Como executar

### Pré-requisitos

- [PHP](https://www.php.net/downloads) 7.4 ou superior
- Um servidor local, como [XAMPP](https://www.apachefriends.org/), [WAMP](https://www.wampserver.com/) ou [Laragon](https://laragon.org/)
- [Git](https://git-scm.com/)

### Passo a passo

1. **Clone o repositório**
   ```bash
   git clone https://github.com/LeonardoGarcia-dev/Site-Ecommerce.git
   ```

2. **Mova o projeto para a pasta do servidor local**
   - XAMPP: `C:\xampp\htdocs\`
   - Laragon: `C:\laragon\www\`

3. **Inicie o servidor** (Apache) pelo painel da ferramenta escolhida.

4. **Acesse no navegador**
   ```
   http://localhost/Site-Ecommerce/
   ```

> 💡 Alternativamente, é possível usar o servidor embutido do PHP, dentro da pasta do projeto:
> ```bash
> php -S localhost:8000
> ```
> E acessar `http://localhost:8000`.

---

## 🤝 Fluxo de trabalho da equipe

O projeto é desenvolvido de forma colaborativa, com cada integrante trabalhando em sua própria branch e integrando as alterações à `main` por meio de **Pull Requests**.

```bash
# Criar uma nova branch
git checkout -b minha-branch

# Registrar as alterações
git add .
git commit -m "Descrição clara da alteração"

# Enviar para o GitHub
git push origin minha-branch
```

Depois, basta abrir um **Pull Request** para a branch `main` e aguardar a revisão.

---

## 🔮 Melhorias futuras

- [ ] Sistema de busca e filtros de produtos
- [ ] Integração com meios de reservas
- [ ] Acompanhamento de pedidos pelo cliente
- [ ] Layout totalmente responsivo para dispositivos móveis

---

## 👥 Autores

Projeto desenvolvido em equipe:

- **Leonardo Garcia** — [@LeonardoGarcia-dev](https://github.com/LeonardoGarcia-dev)
- **Felipe Silva** — [@OFelipeSilvaTI](https://github.com/OFelipeSilvaTI)
- **Felipe Placo** — [@felipeplaco](https://github.com/felipeplaco)
- **Guilherme Cortes** - [@CortesGui07](https://github.com/CortesGui07)

---

<p align="center">Feito com 💙 por estudantes do Colégio Técnico Industrial “Prof. Isaac Portal Roldán” por desenvolvimento web.</p>
