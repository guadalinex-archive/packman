#!/usr/bin/python
# -*- coding: utf-8 -*-
#
# @date 12/02/2007

############################# Class BlockInfo #############################
# Almacenará un bloque de datos, campo/valor, proveniente de los
# ficheros índice Packages/.gz/bz2, Release o Sources/.gz/bz2 .
# En estos ficheros, cada bloque está separado por dos saltos de línea
# 
# @author Junta de Andalucía <devmaster@juntadeandalucia.es>
# @coder Francisco javier Ramos Álvarez <fran.programador@gmail.com>
# @version 1.0
# @package Package
# @see Class FileInfo

class BlockInfo:
    '''
    ' Constructor de la clase
    '
    ' @access public
    ' @param string strBlock. Contiene el bloque leido, previamente, en una cadena
    ' @return BlockInfo
    '''
    def __init__(self, strBlock):
        self.__datas = {}
        
        if strBlock:
            lines = strBlock.split('\n')  # dividimos el bloque en líneas
            for line in lines:
                # nos saltamos los comentarios del tipo '#'
                if not line.strip().startswith('#'):
                    # comprobamos si estamos leyendo campos de varias líneas
                    # que son aquellas que empiezan con un espacio
                    if not line.startswith(' '):
                        field = line.split(': ')[0]
                        self.__datas[field] = line.split(': ')[1] 
                    else:
                        self.__datas[field] = self.__datas[field] + '\n' + line[1:]
        else:
            raise NameError('Empty block')
    
    '''
    ' Este método nos devolverá el valor asociado a un campo
    '
    ' @access public
    ' @param string field
    ' @return string
    '''
    def getValue(self, field):
        if self.__datas.has_key(field):
            return self.__datas[field]
        else:
            return ''
############################### End class ###############################


############################# Class FileInfo #############################
# Cargará los ficheros índices, Packages y Sources, como una lista de bloques.
# En resumen, será como una tabla con registros (bloques de datos).
# 
# @author Francisco Javier Ramos Álvarez
# @version 1.0
# @package Package
# @see Class BlockInfo

import os
import re
import gzip
import bz2

class FileInfo:
    '''
    ' Constructor de la clase.
    '
    ' @access public
    ' @param string path
    ' @return FileInfo
    '''
    def __init__(self, path):
        # atributos privados
        self.__datas = []
        
        if os.path.exists(path):
            self.__dumpContent(path) # volcamos contenido
        else:
            raise NameError('File not found')

    '''
    ' Extrae el contenido del fichero y crea la estructura.
    '
    ' @access private
    ' @param string path
    '''
    def __dumpContent(self, path):
        fd = self.__getResourceFile(path) # obtenemos un recurso para lectura
        
        content = fd.read().split('\n\n')
        fd.close()
        
        for data in content:
            # vamos insertando en un array los bloques de datos
            if data:
                self.__datas.append(BlockInfo(data))

    '''
    ' Abre un recurso de lectura según el formato del fichero.
    '
    ' @access private
    ' @param string path
    ' @return Resource
    '''
    def __getResourceFile(self, path):
        # obtenemos al recurso del fichero según el tipo de éste
        if path.endswith('gz'):
            fd = gzip.open(path, 'r')
        elif path.endswith('bz2'):
            fd = bz2.BZ2File(path, 'r')
        else:
            fd = open(path, 'r')
            
        return fd

    '''
    ' Devuelve la estructura de datos del fichero.
    '
    ' @access public
    ' @return Array of BlockInfo
    '''
    def getDatas(self):
        return self.__datas
############################### End class ###############################

############################# Main funcion #############################
def main():
    path = '/home/fran/Packages.gz'
    packages = FileInfo(path)
    datas = packages.getDatas()
############################### End main ###############################

if __name__ == '__main__':
    main()
