#!/usr/bin/python
# -*- coding: utf-8 -*-
#
# @author Junta de Andalucía <devmaster@juntadeandalucia.es>
# @coder Francisco javier Ramos Álvarez <fran.programador@gmail.com>
# @date 14/02/2007
# @description:
#    script que checkea los repositorios de Guadalinex en 
#    busca de cambios y así mantener actualizada la base 
#    de datos del Packman

# parametros de configuración base de datos
dbhost = 'localhost'
dbuser = 'root'
dbpass = ''
dbname = 'packman3'

# importaciones
import MySQLdb
import urllib
import os
import Package
import sys
import datetime

################################### BEGIN #######################################
try:
    # conectamos con la base de datos
    conn = MySQLdb.connect(dbhost, dbuser, dbpass, dbname)
except MySQLdb.Error:
    print '[!] Error al intentar conectar con la base de datos'
    sys.exit(-1)

try:
    
    cur = conn.cursor()
    
    # montamos la consulta y ejecutamos
    sql = "select rel.id_relation, concat(concat(concat(concat(rep.url, concat('dists/', "
    sql += "concat(dis.name, '/'))), rel.id_branch), concat('/binary-', "
    sql += "rel.id_architecture)), '/Packages.gz') as url_package, rel.temporary_sign "
    sql += "from edition ed inner join repository rep on (ed.id_edition = rep.id_edition) "
    sql += "inner join distribution dis on (rep.id_repository = dis.id_repository) "
    sql += "inner join relation rel on (dis.id_distribution = rel.id_distribution)"
    
    if cur.execute(sql) > 0:
        rows = cur.fetchall()
        
        # redirigimos la salida y creamos un log de progreso
        # saveout = sys.stdout
        # fsock = open('check_' + datetime.date.today().strftime('%Y%m%d') + '.log', 'a')
        # sys.stdout = fsock
        
        print '### ' + datetime.datetime.now().strftime("%H:%M:%S") + " ###"
        # recorremos los distintos Packages.gz
        for row in rows:
            try:
                ufile = urllib.urlopen(row[1]) # abrimos recurso para leer la cabecera
            except IOError:
                print '[!] Error al intentar conectar con ' + row[1]
                sys.exit(-2)
            
            print row[1]
            print 'Chequeamos ultima actualizacion...'
            if ufile.headers.getheader('last-modified') != row[2]: 
                ufile.close()
                
                try:
                    print 'Descargamos fichero Packages.gz...'
                    ufile = urllib.urlretrieve(row[1]) # descargamos fichero
                except IOError:
                    print '[!] Error al intentar descargar Packages.gz'
                    sys.exit(-3)
                
                # a continuación montamos la estructura y la almacenamos
                try:
                    print 'Cargamos estructura Package...'
                    packs = Package.FileInfo(ufile[0])
                except:
                    print '[!] Error al intentar procesar Packages.gz'
                    sys.exit(-4)
                
                datas = packs.getDatas() # obtenemos el contenido estructurado
                
                if not row[2] is None: # actualizamos
                    print 'Actualizamos...'

                    # hemos de comprobar ambos sentidos: 
                    # Packages.gz -> BD          (nuevos paquetes, AnoB)
                    # BD          -> Packages.gz (borrado de paquetes, BnoA)
                  
                    sql = 'select id_package, name, version from package '
                    sql += 'where id_relation = %d order by name, version' %row[0]
                    cur.execute(sql)
                    res = cur.fetchall()
                    
                    # algoritmo que me he sacado de la manga, pero es muy eficiente
                    # y no tiene coste cuadrático sino lineal (existirá?, es posible,
                    # pero ahora mismo no me acuerdo)
                    A = 0
                    B = 0
                    while True:
                        if A < len(datas):
                            vA = datas[A].getValue('Package'), datas[A].getValue('Version')
                        else:
                            vA = 0, 0
                        
                        if B < len(res):
                            vB = res[B][1], res[B][2]
                        else:
                            vB = 0, 0
                            
                        if vA == (0, 0) and vB == (0, 0):
                            break # nos salimos en este punto
                        
                        if vA == vB:
                            A += 1
                            B += 1
                        elif vA != (0, 0) and (vA < vB or vB == (0, 0)): # se añade datas[A]
                            print '(+) %s, %s' %(vA[0], vA[1])
                            # print vB
                            
                            sql = "insert into package (id_relation, name, version, section, "
                            sql += "installed_size, maintainer, architecture, dependes, "
                            sql += "conflicts, recommends, suggests, filename, size, "
                            sql += "description, md5, distributed) values (%d, '%s', '%s', " %(row[0], datas[A].getValue('Package'), datas[A].getValue('Version'))
                            sql += "'%s', '%s', '%s', '%s', " %(datas[A].getValue('Section'), datas[A].getValue('Installed-Size'), MySQLdb.escape_string(datas[A].getValue('Maintainer')), datas[A].getValue('Architecture'))
                            sql += "'%s', '%s', '%s', '%s', " %(datas[A].getValue('Depends'), datas[A].getValue('Conflicts'), datas[A].getValue('Recommends'), datas[A].getValue('Suggests'))
                            sql += "'%s', '%s', '%s', '%s', 'n')" %(datas[A].getValue('Filename'), datas[A].getValue('Size'), MySQLdb.escape_string(datas[A].getValue('Description')), datas[A].getValue('MD5sum'))
                            cur.execute(sql)
                            
                            A += 1
                        elif vB != (0, 0) and (vA > vB or vA == (0, 0)): # se elimina res[B][0]
                            print '(-) %s, %s' %(vB[0], vB[1])
                            # print vA
                            
                            sql = "delete from package where id_package = %d" %res[B][0]
                            cur.execute(sql)
                            
                            B += 1
                    
                else: # cargamos todo el contenido en la base de datos
                    print 'Carga inicial...'
                    
                    for data in datas:
                        sql = "insert into package (id_relation, name, version, section, "
                        sql += "installed_size, maintainer, architecture, dependes, "
                        sql += "conflicts, recommends, suggests, filename, size, "
                        sql += "description, md5, distributed) values (%d, '%s', '%s', " %(row[0], data.getValue('Package'), data.getValue('Version'))
                        sql += "'%s', '%s', '%s', '%s', " %(data.getValue('Section'), data.getValue('Installed-Size'), MySQLdb.escape_string(data.getValue('Maintainer')), data.getValue('Architecture'))
                        sql += "'%s', '%s', '%s', '%s', " %(data.getValue('Depends'), data.getValue('Conflicts'), data.getValue('Recommends'), data.getValue('Suggests'))
                        sql += "'%s', '%s', '%s', '%s', 'n')" %(data.getValue('Filename'), data.getValue('Size'), MySQLdb.escape_string(data.getValue('Description')), data.getValue('MD5sum'))

                        cur.execute(sql)
                    
                    del datas
                
                print 'Actualizamos marca temporal...'  
                sql = "update relation set temporary_sign = '%s' " %ufile[1].getheader('last-modified')
                sql += "where id_relation = %d" %row[0]
                cur.execute(sql) # actualizamos
                
                # eliminamos recursos, borramos fichero
                try:
                    os.remove(ufile[0])
                except OSError:
                    print '[!] No se pudo eliminar el fichero ' + ufile[0]
                    
                del ufile
                del packs
            else:
                print 'No ha habido cambios.'
            
            print '---'
        
        print '\n\n'
        # sys.stdout = saveout
        # fsock.close()
        
    # cerramos recursos
    cur.close()
    conn.close()
    
except MySQLdb.Error, e:
    print '[!] Error %d: %s' %(e.args[0], e.args[1])
    sys.exit(-5)
################################### END #######################################
