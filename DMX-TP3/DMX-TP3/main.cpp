#include <iostream>
#include "TCPServer.h"
#include <winsock2.h>
#include <stdio.h>
#include "DasHard.h"
#include <vector>
#include <string.h>

using namespace std;

#define DMX_MAXCHANNEL 512

#pragma comment(lib, "ws2_32.lib")
typedef int(*DASHARDCOMMAND)(int, int, unsigned char*);
DASHARDCOMMAND DasUsbCommand;
HINSTANCE g_dasusbdll;

int interface_open;
int interface_close;

unsigned char dmxBlock[512];
TCPServer *TCPs = new TCPServer();
const std::vector<std::string> explode(const std::string& msg, const char& c);
const std::vector<std::string> explode(const std::string& msg, const char& c)
{
	std::string buff = "";
	std::vector<std::string> v;
	for (int i = 0; i < msg.length(); i++)
	{
		char compare = msg[i];
		if (compare != c) buff += compare;
		else if (compare == c && buff != "")
		{
			v.push_back(buff);
			buff = "";
		}
	}
	if (buff != "")
		v.push_back(buff);
	return v;
}

void paressend(char *buf) {
	//caractère de fin de chaine
	char buff[2] = { ";" };   

	switch (buf[0]) {

		case   'a':

			//Chargement de la DLL
			g_dasusbdll = LoadLibrary(L"DasHard2006.dll");
			//Verification du chargement
			if (g_dasusbdll) {

				DasUsbCommand = (DASHARDCOMMAND)::GetProcAddress((HMODULE)g_dasusbdll, "DasUsbCommand");


				//appel de la fonction de la lib qui permet à la lampe de s'allumer
				interface_open = DasUsbCommand(DHC_INIT, 0, NULL);



				interface_open = DasUsbCommand(DHC_OPEN, 0, 0);


				if (interface_open > 0)
				{

					int i;
					for (i = 0; i < DMX_MAXCHANNEL; i++) {
						dmxBlock[i] = 0;
					}
					dmxBlock[0] = 255;
					//CMD pour envoyer les commande à la lampe
					DasUsbCommand(DHC_DMXOUT, DMX_MAXCHANNEL, dmxBlock);
				}
			}
			//envoie de la réponse au php
			TCPs->TCPsend(buff);
			
			break;
		case   'r':
			dmxBlock[1] = 0;
			dmxBlock[2] = 0;
			dmxBlock[0] = 255;
			dmxBlock[3] = 0;
			DasUsbCommand(DHC_DMXOUT, DMX_MAXCHANNEL, dmxBlock);
			//envoie de la réponse au php
			TCPs->TCPsend(buff);
			
			break;
		case 'v':
			dmxBlock[0] = 0;
			dmxBlock[2] = 0;
			dmxBlock[1] = 255;
			dmxBlock[3] = 0;
			DasUsbCommand(DHC_DMXOUT, DMX_MAXCHANNEL, dmxBlock);
			//envoie de la réponse au php
			TCPs->TCPsend(buff);
			
			break;
		case 'b' :
			dmxBlock[0] = 0;
			dmxBlock[1] = 0;
			dmxBlock[2] = 255;
			dmxBlock[3] = 0;
			DasUsbCommand(DHC_DMXOUT, DMX_MAXCHANNEL, dmxBlock);
			//envoie de la réponse au php
			TCPs->TCPsend(buff);
			
		break;

		case 'w':

			dmxBlock[0] = 0;
			dmxBlock[1] = 0;
			dmxBlock[2] = 0;
			dmxBlock[3] = 255;


			DasUsbCommand(DHC_DMXOUT, DMX_MAXCHANNEL, dmxBlock);
			//envoie de la réponse au php
			TCPs->TCPsend(buff);
			
		break;

		case 'e':
			dmxBlock[1] = 0;
			dmxBlock[2] = 0;
			dmxBlock[0] = 0;
			dmxBlock[0] = 0;
			dmxBlock[2] = 0;
			dmxBlock[1] = 0;
			dmxBlock[0] = 0;
			dmxBlock[1] = 0;
			dmxBlock[2] = 0;
			dmxBlock[3] = 0;

			DasUsbCommand(DHC_DMXOUT, DMX_MAXCHANNEL, dmxBlock);

			//appel de la fonction de la lib qui permet à la lampe de s'éteindre

			DasUsbCommand(DHC_CLOSE, 0, 0);
			DasUsbCommand(DHC_EXIT, 0, NULL);
			TCPs->TCPsend(buff);

		break;

	default:
		//envoie de la réponse au php
		TCPs->TCPsend(buf);
		vector<string> stringbuf =  explode(buf, ';');
for (int i = 0; i < stringbuf.size(); i++) {
		cout << stringbuf[i].c_str();
	}

		dmxBlock[0] = atoi(stringbuf[0].c_str());
		dmxBlock[1] = atoi(stringbuf[1].c_str());
		dmxBlock[2] = atoi(stringbuf[2].c_str());
		dmxBlock[3] = atoi(stringbuf[3].c_str());

		DasUsbCommand(DHC_DMXOUT, DMX_MAXCHANNEL, dmxBlock);
		TCPs->TCPsend(buff);
		
	};

	cout << endl;


	
}


int main()
{

	bool TCPser = TCPs->TCPconnexion(1234);

	do
	{
		TCPser = TCPs->TCPWaitConnexion();
		if (TCPser == true) {
			char buf[20]= { 0 };
			TCPs->TCPrecv(buf, 20);
			
			paressend(buf);
			TCPs->TCPsend(buf);
			
		
		}
		

	} while (TCPser == true);
}

