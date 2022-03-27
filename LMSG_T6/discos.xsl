<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:template match="/tienda">
    <html>
    <head>
          <link rel="stylesheet" href="discos.css"></link>
    </head>
    <body>
<br/>
      <h2> Tienda del Rock - Listado de productos </h2><br/><br/>
        <table>
        <tr>
          <th>Nombre</th><th>Artista</th><th>Year</th><th>Discografia</th><th>Formato</th>
        </tr>
        <xsl:for-each select="disco">
          <tr>
            <td class = "nombre"><xsl:value-of select="nombre"/></td>
            <td class = "artista"><xsl:value-of select="artista"/></td>
            <td class = "year"><xsl:value-of select="year"/></td>
            <td class = "discogra"><xsl:value-of select="discografia"/></td>
            <td class = "formato"><xsl:value-of select="formato"/></td>
          </tr>

        </xsl:for-each>

        </table>
    </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
