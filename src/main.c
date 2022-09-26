/*
** Jason Brillante "Damdoshi"
** Hanged Bunny Studio 2014-2021
** EFRITS SAS 2022
** Pentacle Technologie 2008-2022
**
** I HIT NFS:
** Infosphere Hand In The Network File System
*/

#include	<lapin.h>
#include	<stdio.h>
#include	"hand.h"

typedef bool	t_commandp(const char		**params);
t_commandp	lock_log_cmd,
		regular_log_cmd,
		pickup_cmd
  ;

t_command	cmd[] = {
  {"locklog", lock_log_cmd},
  {"log", regular_log_cmd},
  {"pick", pickup_cmd}
};

bool		handle_command(const char	**str)
{
  
}

int		main(void)
{
  const char	*toks[] = {
    " ",
    NULL
  };
  t_bunny_split	split;
  int		i;

  while ((i = read(0, &bunny_big_buffer[0], sizeof(bunny_big_buffer) - 1)) > 0)
    {
      bunny_big_buffer[i] = '\0';
      if ((split = bunny_split(&bunny_big_buffer[0], toks, true)) == NULL)
	return (EXIT_FAILURE);

      if (handle_command(split) == false)
	return (EXIT_FAILURE);
      bunny_delete_spliut(split);
    }
  return (EXIT_SUCCESS);
}

