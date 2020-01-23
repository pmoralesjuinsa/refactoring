FROM mysql:latest AS mimysqlcom

EXPOSE 3306

VOLUME /var/lib/mysql

ENV MYSQL_ROOT_PASSWORD 123456
ENV MYSQL_DATABASE retoRefac

ADD cargaDatos.sql /docker-entrypoint-initdb.d/cargaDatos.sql