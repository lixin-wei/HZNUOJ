/*
 * 
 *
 * This file is part of HUSTOJ.
 *
 * HUSTOJ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * HUSTOJ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HUSTOJ. if not, see <http://www.gnu.org/licenses/>.
 */
#include <sys/syscall.h>
#define HOJ_MAX_LIMIT -1
#define CALL_ARRAY_SIZE 512
#ifdef __i386
   #include "okcalls32.h"
#endif
#ifdef __x86_64
   #include "okcalls64.h"
#endif
#ifdef __arm__
   #include "okcalls_arm.h"
#endif
#ifdef __aarch64__
   #include "okcalls_aarch64.h"
#endif
#ifdef __mips__
   #include "okcalls_mips.h"
#endif
