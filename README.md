# **Visão Geral do Projeto: Sistema de Aprovação para o Grupo Rialma**

## **Objetivo**
O sistema é projetado para gerenciar processos de aprovação dentro do **Grupo Rialma**, envolvendo gerentes, diretores e o presidente. Ele simplifica o fluxo de aprovação de pedidos de compra, garantindo que todas as aprovações necessárias sejam obtidas antes que a compra possa prosseguir.

## **Stack Tecnológica**

- ![Laravel](https://img.shields.io/badge/Backend-Laravel-red?style=flat-square&logo=laravel)
- ![Vue.js](https://img.shields.io/badge/Frontend-Vue.js-brightgreen?style=flat-square&logo=vue.js)
- ![PrimeVue](https://img.shields.io/badge/UI-PrimeVue-blue?style=flat-square&logo=primevue)
- ![MySQL](https://img.shields.io/badge/Database-MySQL-blue?style=flat-square&logo=mysql)

---

## **Lógica do Sistema**

### **Cadastro de Pedido Sem Fluxo**
1. **Etapa 1:** Cadastrar um pedido no menu **"Cadastro de Pedido Sem Fluxo"**, selecionando o presidente que precisa aprovar.
2. **Etapa 2:** O pedido passa por uma análise de fluxo para validar os dados, verificar PDFs e outros requisitos.
3. **Etapa 3:** Se o pedido for reprovado durante a análise de fluxo, ele é devolvido ao comprador para ser modificado e justificado conforme o feedback do gestor de fluxo.
4. **Etapa 4:** Se aprovado pelo gestor de fluxo, o pedido segue para o presidente para aprovação final.
5. **Etapa 5:** Se o presidente reprovar o pedido, ele é devolvido ao comprador para modificação e justificativa.
6. **Etapa 6:** Se o presidente aprovar o pedido, ele é devolvido ao comprador com status de aprovado, permitindo que ele gere a autorização de compra.

### **Cadastro de Pedido Com Fluxo**
1. **Etapa 1:** Cadastrar um pedido no menu **"Cadastro de Pedido Com Fluxo"**, selecionando os gerentes e diretores que precisam aprovar.
2. **Etapa 2:** O pedido é enviado para cada gerente e diretor selecionado para aprovação. Assim que aprovado, ele segue para o gestor de fluxo para validação das informações e anexos.
3. **Etapa 3:** Se algum gerente ou diretor reprovar o pedido, ele é devolvido ao comprador para justificativa.
4. **Etapa 4:** Assim que o gestor de fluxo aprovar o pedido, ele segue para o presidente selecionado durante o cadastro do pedido.
5. **Etapa 5:** Se o presidente reprovar o pedido, ele é devolvido ao diretor ou gerente da área para justificativa.
6. **Etapa 6:** Se o presidente aprovar o pedido, ele é devolvido ao comprador para gerar a autorização de compra.

---

Este sistema garante que todas as aprovações necessárias sejam gerenciadas de forma sistemática, proporcionando um fluxo de trabalho claro para os pedidos de compra, reduzindo erros e aumentando a responsabilidade.
