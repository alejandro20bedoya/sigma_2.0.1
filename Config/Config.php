<?php
const BASE_URL = "http://localhost/sigma-tps";

//Zona horaria
date_default_timezone_set('America/Bogota');

//Datos de conexión a Base de Datos
const DB_HOST = "localhost:3307";
const DB_NAME = "sigma";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_CHARSET = "utf8";

//Datos envio de correo
const NOMBRE_REMITENTE = "SENA La Jagua";
const EMAIL_REMITENTE = "sena.lajagua@sena.edu.co";
const NOMBRE_EMPESA = "SENA";
const WEB_EMPRESA = "www.sena.edu.co";

const DESCRIPCION = "Servicio Nacional de Aprendizaje";
const SHAREDHASH = "Servicio Nacional de Aprendizaje";

//Datos Empresa
const DIRECCION = "La Jagua - Cesar, Colombia";
const TELEMPRESA = "3114689298";
const WHATSAPP = "3114689298";
const EMAIL_EMPRESA = "sena.lajagua@sena.edu.co";
const EMAIL_CONTACTO = "sena.lajagua@sena.edu.co";

const CAT_SLIDER = "1,2,3";
const CAT_BANNER = "4,5,6";
const CAT_FOOTER = "1,2,3,4,5";

//Datos para Encriptar / Desencriptar
const KEY = 'joseramos';
const METHODENCRIPT = "AES-128-ECB";

//Módulos
const MDADMINISTRADOR = 1;
const MDPROGRAMAS = 2;
const MDINICIO = 3;

//Páginas
const PINICIO = 1;

//Roles
const RADMINISTRADOR = 1;
const RCOORDINADOR = 2;
const RADMINISTRATIVO = 3;
const RINSTRUCTOR = 4;