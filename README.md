# 💊 PharmaControl - ERP Farmacêutico Robusto

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)

![Livewire](https://img.shields.io/badge/Livewire-3-4e56a6?style=for-the-badge&logo=livewire&logoColor=white)

![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

![AlpineJS](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)

> Sistema de Gestão para Farmácias e Clínicas com controle avançado de estoque por Lotes (Batches), algoritmo PEPS (Primeiro a Entrar, Primeiro a Sair), PDV ágil e Auditoria de Segurança.

---

## 🚀 Sobre o Projeto

O **PharmaControl** foi desenvolvido para resolver o maior desafio do varejo farmacêutico: **Rastreabilidade e Validade**.

Diferente de sistemas comuns que apenas contam quantidades, este projeto gerencia **Lotes de Validade**. Ao realizar uma venda, o sistema automaticamente baixa o estoque do lote que vence mais cedo (lógica FIFO/PEPS), garantindo eficiência logística e prevenindo perdas.

### Principais Funcionalidades

* **📦 Gestão Inteligente de Estoque:** Controle de múltiplos lotes por produto.
* **🔄 Algoritmo PEPS:** Baixa automática do lote mais antigo no momento da venda.
* **🛒 PDV (Frente de Caixa):** Suporte a **Leitor de Código de Barras**, busca rápida e máscaras de input.
* **🔐 ACL (Controle de Acesso):** Hierarquia de permissões (Admin, Gerente, Vendedor) via Laravel Gates.
* **👮 Auditoria (Logs):** Rastreamento automático de quem criou, editou ou excluiu registros (usando Observers).
* **📄 Cupom Não-Fiscal:** Geração de recibos formatados para impressoras térmicas (80mm).
* **🛑 Segurança Ativa:** Middleware que derruba a sessão instantaneamente se o usuário for desativado.

---

## 🛠️ Arquitetura e Tecnologias

O projeto segue a arquitetura Monolítica Moderna (TALL Stack):

* **Backend:** Laravel 12 (PHP 8.2+)
* **Frontend:** Blade + Livewire 3 (Reatividade sem sair do PHP)
* **Micro-interações:** Alpine.js (Modais, Máscaras, Gráficos)
* **Estilização:** Tailwind CSS
* **Banco de Dados:** MySQL

### Destaques de Código (Design Patterns)
1.  **Service Pattern:** Regras de negócio complexas (como a baixa de estoque PEPS e o estorno de vendas) isoladas em `SaleService`.
2.  **Database Transactions:** Garantia de integridade ACID nas vendas e cancelamentos.
3.  **Observers:** Monitoramento silencioso de Models para gerar logs de auditoria.
4.  **Scopes & Accessors:** Encapsulamento de lógica de consulta (ex: `total_stock` calculado dinamicamente).

---

## 🧭 Rotas e Módulos do Sistema

O sistema é protegido por autenticação e dividido por níveis de acesso (Roles).

### 🟢 Acesso Geral (Todos os Logados)
| Rota | Controller/Componente | Descrição |
| :--- | :--- | :--- |
| `/` | `Dashboard::class` | Visão geral. Vendedores veem resumo pessoal; Gerentes veem faturamento global. |
| `/venda` | `CreateSale::class` | **PDV**. Frente de caixa com leitor de barras e carrinho de compras. |
| `/vendas/historico` | `SalesHistory::class` | Histórico de vendas, filtros de data, reimpressão de cupom e cancelamento. |
| `/venda/{id}/cupom` | `CupomController` | Gera o HTML minimalista para impressão térmica. |

### 🔵 Acesso Gerencial (Gerentes e Admins)
| Rota | Controller/Componente | Descrição |
| :--- | :--- | :--- |
| `/produtos` | `ListProducts::class` | Listagem de produtos com alerta visual de estoque baixo. |
| `/produtos/novo` | `CreateProduct::class` | Cadastro de produtos com máscaras de preço. |
| `/produtos/{id}/editar`| `EditProduct::class` | Edição de dados e **Gestão Manual de Lotes** (Ajuste de estoque). |
| `/estoque/entrada` | `StockEntry::class` | Entrada de nota fiscal. Busca produto e lança novo lote/validade. |

### 🔴 Acesso Administrativo (Apenas Admin)
| Rota | Controller/Componente | Descrição |
| :--- | :--- | :--- |
| `/usuarios` | `ManageUsers::class` | CRUD de funcionários. Criação de contas e bloqueio de acesso (Status). |
| `/auditoria` | `AuditLogs::class` | Visualização dos logs de segurança do sistema (Quem fez o quê). |

