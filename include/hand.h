/*
** Jason Brillante "Damdoshi"
** Hanged Bunny Studio 2014-2021
** EFRITS SAS 2022
** Pentacle Technologie 2008-2022
**
** I HIT NFS:
** Infosphere Hand In The Network File System
*/

#ifndef		__HAND_H__
# define	__HAND_H__

typedef bool	(*t_commandf)(const char	**params);

typedef struct	s_command
{
  const char	*cmd;
  t_commandf	func;
}		t_command;

#endif	/*	__HAND_H__	*/
