FROM rabbitmq:3.7.2-management-alpine

COPY ./plugins/* /plugins

RUN rabbitmq-plugins enable rabbitmq_delayed_message_exchange

ENTRYPOINT ["bash","-c"]
CMD ["docker-entrypoint.sh rabbitmq-server"]
