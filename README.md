# PharmaControl - Sistema de Gestão Farmacêutica

O **PharmaControl** é um ERP desenvolvido para o varejo farmacêutico, com foco rigoroso em rastreabilidade de lotes, controle de validade e integridade financeira.

O sistema diferencia-se por implementar uma gestão de estoque baseada no método **PEPS** (Primeiro a Entrar, Primeiro a Sair), garantindo que o custo da mercadoria vendida (CMV) e o lucro sejam calculados com precisão contábil, além de oferecer camadas de segurança avançada para proteção de dados sensíveis.

## Arquitetura e Padrões de Projeto

O projeto foi construído seguindo princípios de arquitetura limpa e robusta para garantir escalabilidade e manutenção:

* **Service Pattern:** Lógica de negócios complexa (como baixa de estoque e estorno) isolada em classes de serviço (`SaleService`), mantendo os Controllers leves.
* **Database Transactions:** Uso estrito de transações ACID (Atomicity, Consistency, Isolation, Durability) para garantir que vendas e cancelamentos mantenham a integridade do banco de dados.
* **Observers:** Implementação de *Model Observers* para auditoria automática de alterações em registros críticos.
* **Single Page Application (SPA) feel:** Utilização do Livewire para navegação fluida e atualizações de estado sem recarregamento de página.

## Módulos e Funcionalidades

### 1. Gestão de Estoque e Lotes
O núcleo do sistema. Diferente de controles simples de quantidade, o PharmaControl gerencia unidades vinculadas a lotes específicos.
* **Algoritmo PEPS/FIFO:** No momento da venda, o sistema identifica e baixa automaticamente o lote com a data de validade mais próxima.
* **Entrada de Nota:** Interface dedicada para registro de novos lotes, custos e validades.
* **Alertas de Vencimento:** Monitoramento automático de produtos próximos ao vencimento (14 e 30 dias).
* **Disparo de E-mails:** Rotina agendada e manual para notificar gerentes sobre perdas iminentes por validade.

### 2. Frente de Caixa (PDV)
Interface otimizada para agilidade no atendimento ao balcão.
* **Busca Híbrida:** Suporte para leitura de código de barras (EAN/SKU) ou busca textual inteligente.
* **Vínculo de Clientes:** Modal integrado para cadastro rápido de clientes sem sair da tela de venda.
* **Impressão Térmica:** Geração de comprovante não-fiscal formatado para impressoras de 80mm.

### 3. Financeiro e Inteligência de Negócio
Ferramentas para análise real da saúde do negócio.
* **Cálculo de Lucratividade Real:** Relatórios que cruzam o valor da venda com o custo específico do lote vendido (não custo médio), gerando a margem de lucro exata.
* **Exportação de Dados:** Geração de planilhas Excel para contabilidade.
* **Cancelamento com Estorno:** Processo de reversão de vendas que devolve os itens para seus respectivos lotes de origem automaticamente.
* **Dashboard:** Visualização gráfica de faturamento, ticket médio e alertas críticos.

### 4. Segurança e Auditoria
Camada de proteção corporativa para controle de acesso e dados.
* **Autenticação de Dois Fatores (2FA):** Integração com Google Authenticator/Authy para acesso administrativo.
* **Controle de Acesso (ACL):** Hierarquia de permissões baseada em cargos (Admin, Gerente, Vendedor) utilizando Laravel Gates.
* **Logs de Atividade:** Rastreamento detalhado (Quem, Quando, O Quê) de criações, edições e exclusões no sistema.
* **Proteção de Sessão:** Middleware de verificação de status que encerra sessões ativas imediatamente caso o usuário seja desativado.

### 5. CRM (Gestão de Clientes)
* Histórico de compras por cliente.
* Validação e formatação de CPF.
* Identificação de clientes recorrentes no PDV.

## Infraestrutura e DevOps

O sistema está configurado para operar em ambientes de produção de alta performance (VPS):
* **Hardening Nginx:** Configurações avançadas de cabeçalhos de segurança (HSTS, X-Frame-Options, XSS-Protection).
* **Content Security Policy (CSP):** Políticas restritivas para prevenir injeção de scripts maliciosos.

## Stack Tecnológica

* **Backend:** PHP 8.2+, Laravel 12
* **Frontend:** Blade, Livewire 3, Alpine.js
* **Banco de Dados:** MySQL
* **Estilização:** Tailwind CSS
* **Relatórios:** Maatwebsite Excel, Chart.js
* **Segurança:** PragmaRX Google2FA, BaconQrCode
