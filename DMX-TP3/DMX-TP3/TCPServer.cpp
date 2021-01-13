#include "TCPServer.h"

using namespace std;

TCPServer::TCPServer() {
	WSADATA wsa;
	WSAStartup(MAKEWORD(2, 2), &wsa);
	sock = socket(AF_INET, SOCK_STREAM, 0);
	buffer = new char[1024];
}
bool TCPServer::TCPconnexion(int PORT) {

	SOCKADDR_IN sin = { 0 };

	sin.sin_addr.s_addr = htonl(INADDR_ANY);

	sin.sin_family = AF_INET;

	sin.sin_port = htons(PORT);

	if (bind(sock, (SOCKADDR *)&sin, sizeof sin) == SOCKET_ERROR)
	{
		return false;
	}
	if (listen(sock, 1) == SOCKET_ERROR)
	{
		return false;
	}


	return true;
}

bool TCPServer::TCPWaitConnexion()
{
	SOCKADDR_IN csin = { 0 };
	int sinsize = sizeof csin;
	csock = accept(sock, (SOCKADDR *)&csin, &sinsize);

	if (csock == INVALID_SOCKET)
	{
		return false;
	}
	return true;
}

bool TCPServer::TCPsend(char *buffer) {

	if (send(csock, buffer, strlen(buffer), 0) < 0)
	{
		return false;
	}

	return true;
}
bool TCPServer::TCPrecv(char *buffer, int len) {

	int n = 0;

	if ((n = recv(csock, buffer, len, 0)) <= 0)
	{
		return false;
	}

	buffer[n] = '\0';
	return true;
}

bool TCPServer::TCPclose() {
	closesocket(sock);
	closesocket(csock);
	return true;
}

