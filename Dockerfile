FROM adriaprats/ubuntu_ws:08.06
RUN apt update && apt upgrade -y
CMD service apache2 restart